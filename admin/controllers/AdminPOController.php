<?php
require_once ADMIN . '/AdminController.php';
require_once APP   . '/models/StockModel.php';

class AdminPOController extends AdminController {

    public function index(array $p = []): void {
        $pageTitle = 'Purchase Orders';
        $db        = $this->db->connect();
        $search    = Helper::sanitize($_GET['search'] ?? '');
        $status    = $db->real_escape_string($_GET['status'] ?? '');
        $perPage   = in_array((int)($_GET['per_page'] ?? 25), [10,25,50,100]) ? (int)($_GET['per_page'] ?? 25) : 25;
        $page      = max(1, (int)($_GET['page'] ?? 1));
        $offset    = ($page - 1) * $perPage;

        $where = '1=1';
        if ($status) $where .= " AND po.status = '$status'";
        if ($search) {
            $sq     = $db->real_escape_string($search);
            $where .= " AND (po.po_number LIKE '%$sq%' OR v.company_name LIKE '%$sq%')";
        }

        $total = (int)($this->db->selectOne(
            "SELECT COUNT(*) AS cnt
             FROM purchase_orders po
             LEFT JOIN vendors v ON v.id = po.vendor_id
             WHERE $where"
        )['cnt'] ?? 0);

        $orders = $this->db->select(
            "SELECT po.*, v.company_name,
                    (SELECT COUNT(*) FROM purchase_order_items WHERE po_id=po.id) AS item_count
             FROM purchase_orders po
             LEFT JOIN vendors v ON v.id = po.vendor_id
             WHERE $where
             ORDER BY po.created_at DESC
             LIMIT $perPage OFFSET $offset"
        ) ?: [];

        $pagData = Helper::paginate($total, $perPage, $page);
        $this->view('purchase_orders/index', compact('pageTitle','orders','search','status','perPage','pagData','total'));
    }

    public function create(array $p = []): void {
        $pageTitle = 'Create Purchase Order';
        $vendors   = $this->db->select("SELECT * FROM vendors WHERE status='active' ORDER BY company_name") ?: [];
        $products  = $this->db->select(
            "SELECT p.id, p.name, p.sku, COALESCE(ps.quantity,0) AS stock_qty,
                    p.cost_price
             FROM products p
             LEFT JOIN product_stock ps ON ps.product_id=p.id AND ps.variation_id IS NULL AND ps.warehouse='Main'
             WHERE p.status='active' ORDER BY p.name"
        ) ?: [];
        $this->view('purchase_orders/create', compact('pageTitle','vendors','products'));
    }

    public function store(array $p = []): void {
        CSRF::check();

        $vendorId = (int)($_POST['vendor_id'] ?? 0);
        $notes    = Helper::sanitize($_POST['notes'] ?? '');
        $expDate  = Helper::sanitize($_POST['expected_date'] ?? '');

        if (!$vendorId) { $this->json(['success'=>false,'message'=>'Select a vendor.']); }

        $items      = $_POST['items'] ?? [];
        $validItems = array_filter($items, fn($i) => !empty($i['product_id']) && $i['quantity'] > 0 && $i['unit_price'] > 0);

        if (empty($validItems)) { $this->json(['success'=>false,'message'=>'Add at least one item.']); }

        $total    = 0;
        $poNumber = 'PO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        $poId = $this->db->insert('purchase_orders', [
            'po_number'     => $poNumber,
            'vendor_id'     => $vendorId,
            'status'        => 'draft',
            'notes'         => $notes,
            'expected_date' => $expDate ?: null,
            'created_by'    => Auth::id(),
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        foreach ($validItems as $item) {
            $pid      = (int)$item['product_id'];
            $qty      = (int)$item['quantity'];
            $price    = (float)$item['unit_price'];
            $linTotal = $qty * $price;
            $total   += $linTotal;

            $this->db->insert('purchase_order_items', [
                'po_id'       => $poId,
                'product_id'  => $pid,
                'quantity'    => $qty,
                'unit_price'  => $price,
                'total_price' => $linTotal,
            ]);
        }

        $this->db->update('purchase_orders', ['total_amount'=>$total], "id=$poId");
        $this->db->logActivity(Auth::id(), 'CREATE', 'purchase_orders', $poId, null, ['po_number'=>$poNumber]);

        $this->json(['success'=>true,'message'=>"PO $poNumber created.",'redirect'=>url("admin/purchase-orders/view/$poId")]);
    }

    public function show(array $params): void {
        $pageTitle = 'Purchase Order Details';
        $id = (int)($params['id'] ?? 0);
        $po = $this->db->selectOne(
            "SELECT po.*, v.company_name, v.email AS vendor_email, v.phone AS vendor_phone,
                    u.full_name AS created_by_name
             FROM purchase_orders po
             LEFT JOIN vendors v ON v.id = po.vendor_id
             LEFT JOIN users u   ON u.id = po.created_by
             WHERE po.id = $id"
        );
        if (!$po) { $this->redirect('admin/purchase-orders'); }

        $items = $this->db->select(
            "SELECT poi.*, p.name AS product_name, p.sku, p.thumbnail,
                    pv.name AS variation_name
             FROM purchase_order_items poi
             JOIN products p ON p.id = poi.product_id
             LEFT JOIN product_variations pv ON pv.id = poi.variation_id
             WHERE poi.po_id = $id"
        ) ?: [];

        $this->view('purchase_orders/view', compact('pageTitle','po','items'));
    }

    public function submit(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['po_id'] ?? 0);
        $this->db->update('purchase_orders', ['status'=>'pending'], "id=$id AND status='draft'");
        $this->db->logActivity(Auth::id(), 'SUBMIT', 'purchase_orders', $id);
        $this->json(['success'=>true,'message'=>'Purchase Order submitted for approval.']);
    }

    public function approve(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['po_id'] ?? 0);
        $this->db->update('purchase_orders', ['status'=>'approved','approved_by'=>Auth::id()], "id=$id AND status='pending'");
        $this->db->logActivity(Auth::id(), 'APPROVE', 'purchase_orders', $id);
        $this->json(['success'=>true,'message'=>'Purchase Order approved.']);
    }

    public function receive(array $p = []): void {
        CSRF::check();
        $id  = (int)($_POST['po_id'] ?? 0);
        $res = (new StockModel())->receivePO($id, Auth::id());
        $this->db->logActivity(Auth::id(), 'RECEIVE', 'purchase_orders', $id);
        $this->json($res);
    }

    public function cancel(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['po_id'] ?? 0);
        $this->db->update('purchase_orders', ['status'=>'cancelled'], "id=$id AND status IN ('draft','pending')");
        $this->json(['success'=>true,'message'=>'Purchase Order cancelled.']);
    }
}
