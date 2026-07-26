<?php
require_once ADMIN . '/AdminController.php';
require_once APP   . '/models/HomeSectionModel.php';

class AdminHomeSectionsController extends AdminController {

    private HomeSectionModel $hs;

    public function __construct() {
        parent::__construct();
        $this->hs = new HomeSectionModel();
        // Ensure upload directory exists
        if (!is_dir(ROOT.'/uploads/home')) {
            mkdir(ROOT.'/uploads/home', 0755, true);
        }
    }

    // ── GET /admin/home-sections ──────────────────────
    public function index(array $p = []): void {
        $pageTitle   = 'Homepage Sections';
        $activeTab   = $_GET['tab'] ?? 'hero';
        $heroItems   = $this->hs->getHeroSections(false);
        $collections = $this->hs->getCollections(false);
        $reviews     = $this->hs->getReviews(false);
        $categories  = Helper::categories();
        $this->view('home_sections/index', compact(
            'pageTitle','activeTab','heroItems','collections','reviews','categories'
        ));
    }

    // ── Hero ──────────────────────────────────────────
    public function heroStore(array $p = []): void {
        CSRF::check();
        $image = $this->uploadImage('image');
        $id = $this->hs->saveHeroSection(array_merge($_POST, $image ? ['image'=>$image] : []));
        $this->json(['success'=>true,'message'=>'Hero section added.','id'=>$id]);
    }

    public function heroUpdate(array $p = []): void {
        CSRF::check();
        $id    = (int)($_POST['id'] ?? 0);
        if (!$id) { $this->json(['success'=>false,'message'=>'Invalid ID.']); return; }
        $image = $this->uploadImage('image');
        $this->hs->saveHeroSection(array_merge($_POST, $image ? ['image'=>$image] : []), $id);
        $this->json(['success'=>true,'message'=>'Hero section updated.']);
    }

    public function heroDelete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if ($id) $this->hs->deleteHeroSection($id);
        $this->json(['success'=>true,'message'=>'Deleted.']);
    }

    public function heroToggle(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if ($id) $this->hs->toggleHeroSection($id);
        $this->json(['success'=>true]);
    }

    // ── Collections ───────────────────────────────────
    public function collectionStore(array $p = []): void {
        CSRF::check();
        $image = $this->uploadImage('image');
        $id = $this->hs->saveCollection(array_merge($_POST, $image ? ['image'=>$image] : []));
        $this->json(['success'=>true,'message'=>'Collection added.','id'=>$id]);
    }

    public function collectionUpdate(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { $this->json(['success'=>false,'message'=>'Invalid ID.']); return; }
        $image = $this->uploadImage('image');
        $this->hs->saveCollection(array_merge($_POST, $image ? ['image'=>$image] : []), $id);
        $this->json(['success'=>true,'message'=>'Collection updated.']);
    }

    public function collectionDelete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if ($id) $this->hs->deleteCollection($id);
        $this->json(['success'=>true,'message'=>'Deleted.']);
    }

    public function collectionToggle(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if ($id) $this->hs->toggleCollection($id);
        $this->json(['success'=>true]);
    }

    // ── Reviews ───────────────────────────────────────
    public function reviewStore(array $p = []): void {
        CSRF::check();
        $id = $this->hs->saveReview($_POST);
        $this->json(['success'=>true,'message'=>'Review added.','id'=>$id]);
    }

    public function reviewUpdate(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { $this->json(['success'=>false,'message'=>'Invalid ID.']); return; }
        $this->hs->saveReview($_POST, $id);
        $this->json(['success'=>true,'message'=>'Review updated.']);
    }

    public function reviewDelete(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if ($id) $this->hs->deleteReview($id);
        $this->json(['success'=>true,'message'=>'Deleted.']);
    }

    public function reviewToggle(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if ($id) $this->hs->toggleReview($id);
        $this->json(['success'=>true]);
    }

    // ── Drag-drop reorder ─────────────────────────────
    public function reorder(array $p = []): void {
        CSRF::check();
        $table = $_POST['table'] ?? '';
        $ids   = json_decode($_POST['ids'] ?? '[]', true);
        if (is_array($ids)) $this->hs->updateOrder($table, $ids);
        $this->json(['success'=>true]);
    }

    // ── Image upload helper ───────────────────────────
    private function uploadImage(string $field): ?string {
        if (empty($_FILES[$field]['name'])) return null;
        if (($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return null;
        return Helper::uploadImage($_FILES[$field], 'home') ?: null;
    }
}
