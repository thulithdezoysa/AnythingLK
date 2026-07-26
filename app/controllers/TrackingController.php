<?php
require_once CORE . '/Controller.php';
require_once APP  . '/models/TrackingModel.php';

// ============================================================
// TrackingController
// ============================================================
class TrackingController extends Controller {
    public function view(array $p = []): void {
        $pid = (int)($_POST['product_id'] ?? 0);
        if ($pid) (new TrackingModel())->track(Auth::id(), session_id(), $pid, 'view');
        $this->json(['ok'=>true]);
    }
    public function click(array $p = []): void {
        $pid = (int)($_POST['product_id'] ?? 0);
        if ($pid) (new TrackingModel())->track(Auth::id(), session_id(), $pid, 'click');
        $this->json(['ok'=>true]);
    }
}
