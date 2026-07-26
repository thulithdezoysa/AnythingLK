<?php
require_once CORE . '/Controller.php';
require_once APP  . '/models/ChatModel.php';

class ChatController extends Controller {

    private ChatModel $cm;

    public function __construct() {
        parent::__construct();
        $this->cm = new ChatModel();
    }

    // POST /chat/start — return or create session
    public function start(array $p = []): void {
        CSRF::check();
        $sid = $this->cm->getOrCreateSession();
        $msgs = $this->cm->getMessages($sid);

        // First visit — send greeting
        if (empty($msgs)) {
            $name = Auth::id() ? (Auth::user()['name'] ?? 'there') : 'there';
            $greeting = "Hi $name! 👋 I'm the Anything.lk assistant. How can I help you today?";
            $this->cm->saveMessage($sid, 'bot', $greeting);
            $msgs = $this->cm->getMessages($sid);
        }

        $this->json(['success' => true, 'session_id' => $sid, 'messages' => $this->formatMessages($msgs)]);
    }

    // POST /chat/send — user sends a message
    public function send(array $p = []): void {
        CSRF::check();
        $sid     = (int)($_POST['session_id'] ?? 0);
        $message = trim(Helper::sanitize($_POST['message'] ?? ''));
        $lastId  = (int)($_POST['last_id'] ?? 0);

        if (!$sid || $message === '') {
            $this->json(['success' => false, 'message' => 'Invalid input.']);
        }

        $session = $this->cm->getSession($sid);
        if (!$session || $session['status'] === 'closed') {
            $this->json(['success' => false, 'message' => 'Session closed.']);
        }

        // Save user message
        $this->cm->saveMessage($sid, 'user', $message);

        // Find bot reply
        $reply = $this->cm->matchReply($message) ?? $this->cm->getFallbackReply();
        $this->cm->saveMessage($sid, 'bot', $reply);

        // Return only new messages since lastId (avoids client-side duplication)
        $msgs = $this->cm->getMessagesSince($sid, $lastId);
        $this->json(['success' => true, 'messages' => $this->formatMessages($msgs)]);
    }

    // GET /chat/poll?session_id=X&last_id=Y — long-poll for new messages (admin replies)
    public function poll(array $p = []): void {
        $sid    = (int)($_GET['session_id'] ?? 0);
        $lastId = (int)($_GET['last_id'] ?? 0);
        if (!$sid) { $this->json(['success' => false]); }

        $msgs = $this->cm->getMessagesSince($sid, $lastId);
        $this->json(['success' => true, 'messages' => $this->formatMessages($msgs)]);
    }

    // POST /chat/close
    public function close(array $p = []): void {
        CSRF::check();
        $sid = (int)($_POST['session_id'] ?? 0);
        if ($sid) $this->cm->closeSession($sid);
        $this->json(['success' => true]);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    private function formatMessages(array $msgs): array {
        return array_map(fn($m) => [
            'id'      => (int)$m['id'],
            'sender'  => $m['sender'],
            'message' => $m['message'],
            'time'    => date('h:i A', strtotime($m['created_at'])),
        ], $msgs);
    }
}
