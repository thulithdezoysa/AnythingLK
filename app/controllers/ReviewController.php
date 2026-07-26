<?php
require_once CORE . '/Controller.php';

class ReviewController extends Controller {
    public function submit(array $params = []): void {
        if (!Auth::check()) { $this->json(['success'=>false,'message'=>'Please login to review.']); }
        CSRF::check();

        $pid    = (int)($_POST['product_id'] ?? 0);
        $rating = min(5, max(1, (int)($_POST['rating'] ?? 5)));
        $title  = Helper::sanitize($_POST['title'] ?? '');
        $body   = Helper::sanitize($_POST['body'] ?? '');
        $uid    = Auth::id();

        if (!$pid || !$body) { $this->json(['success'=>false,'message'=>'Invalid review.']); }

        // Prevent duplicate
        $dup = $this->db->selectOne("SELECT id FROM product_reviews WHERE product_id=$pid AND user_id=$uid");
        if ($dup) { $this->json(['success'=>false,'message'=>'You already reviewed this product.']); }

        $this->db->insert('product_reviews', [
            'product_id' => $pid,
            'user_id'    => $uid,
            'rating'     => $rating,
            'title'      => $title,
            'body'       => $body,
            'status'     => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->json(['success'=>true,'message'=>'Review submitted and awaiting approval.']);
    }
}
