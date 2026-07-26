<?php
require_once APP . '/models/ChatModel.php';

class AdminChatController extends AdminController {

    private ChatModel $cm;

    public function __construct() {
        parent::__construct();
        $this->cm = new ChatModel();
    }

    // GET admin/chat
    public function index(array $p = []): void {
        $search   = trim($_GET['q'] ?? '');
        $sessions = $this->cm->getAllSessions($search);
        $this->view('chat/index', compact('sessions', 'search'));
    }

    // GET admin/chat/view/{id}
    public function show(array $p = []): void {
        $id      = (int)($p['id'] ?? 0);
        $session = $this->cm->getSession($id);
        if (!$session) { http_response_code(404); echo 'Not found'; return; }

        $messages = $this->cm->getMessages($id);
        $this->view('chat/view', compact('session', 'messages'));
    }

    // POST admin/chat/reply
    public function reply(array $p = []): void {
        CSRF::check();
        $sid     = (int)($_POST['session_id'] ?? 0);
        $message = trim(Helper::sanitize($_POST['message'] ?? ''));
        if (!$sid || $message === '') { $this->json(['success' => false, 'message' => 'Empty message.']); }

        $newId = $this->cm->saveMessage($sid, 'admin', $message);
        $this->json(['success' => true, 'time' => date('h:i A'), 'id' => $newId]);
    }

    // POST admin/chat/close
    public function close(array $p = []): void {
        CSRF::check();
        $sid = (int)($_POST['session_id'] ?? 0);
        if ($sid) $this->cm->closeSession($sid);
        $this->json(['success' => true]);
    }

    // GET admin/chat/auto-replies
    public function autoReplies(array $p = []): void {
        $replies = $this->cm->getAllReplies();
        $this->view('chat/auto_replies', compact('replies'));
    }

    // POST admin/chat/auto-replies/save
    public function saveReply(array $p = []): void {
        CSRF::check();
        $id      = (int)($_POST['id'] ?? 0);
        $keyword = trim(Helper::sanitize($_POST['keyword'] ?? ''));
        $reply   = trim($_POST['reply'] ?? '');
        $status  = (int)($_POST['status'] ?? 1);

        if ($keyword === '' || $reply === '') {
            $this->json(['success' => false, 'message' => 'Keyword and reply are required.']);
        }
        $this->cm->saveReply($id, $keyword, $reply, $status);
        $this->json(['success' => true, 'message' => $id ? 'Updated.' : 'Added.']);
    }

    // POST admin/chat/auto-replies/delete
    public function deleteReply(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if ($id) $this->cm->deleteReply($id);
        $this->json(['success' => true]);
    }

    // POST admin/chat/auto-replies/toggle
    public function toggleReply(array $p = []): void {
        CSRF::check();
        $id = (int)($_POST['id'] ?? 0);
        if ($id) $this->cm->toggleReply($id);
        $this->json(['success' => true]);
    }

    // GET admin/chat/messages?session_id=X&last_id=Y  (admin poll)
    public function messages(array $p = []): void {
        $sid    = (int)($_GET['session_id'] ?? 0);
        $lastId = (int)($_GET['last_id'] ?? 0);
        $msgs   = $this->cm->getMessagesSince($sid, $lastId);
        $out = array_map(fn($m) => [
            'id'      => (int)$m['id'],
            'sender'  => $m['sender'],
            'message' => nl2br(e($m['message'])),
            'time'    => date('h:i A', strtotime($m['created_at'])),
        ], $msgs);
        $this->json(['success' => true, 'messages' => $out]);
    }
}
