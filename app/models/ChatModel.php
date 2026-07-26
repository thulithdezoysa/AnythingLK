<?php
class ChatModel extends Db {

    // ── Sessions ─────────────────────────────────────────────────────

    public function getOrCreateSession(): int {
        $conn    = $this->connect();
        $userId  = Auth::id() ?: null;
        $guestId = session_id();

        if ($userId) {
            $row = $this->selectOne("SELECT id FROM chat_sessions WHERE user_id=$userId AND status='open' ORDER BY id DESC LIMIT 1");
        } else {
            $esc = $conn->real_escape_string($guestId);
            $row = $this->selectOne("SELECT id FROM chat_sessions WHERE guest_id='$esc' AND status='open' ORDER BY id DESC LIMIT 1");
        }

        if ($row) return (int)$row['id'];

        // Create new
        $esc = $conn->real_escape_string($guestId);
        if ($userId) {
            $conn->query("INSERT INTO chat_sessions (user_id, guest_id) VALUES ($userId, '$esc')");
        } else {
            $conn->query("INSERT INTO chat_sessions (guest_id) VALUES ('$esc')");
        }
        return (int)$conn->insert_id;
    }

    public function getSession(int $id): ?array {
        return $this->selectOne("SELECT * FROM chat_sessions WHERE id=$id") ?: null;
    }

    public function closeSession(int $id): void {
        $this->connect()->query("UPDATE chat_sessions SET status='closed' WHERE id=$id");
    }

    public function getAllSessions(string $search = '', int $limit = 50): array {
        $where = "1";
        if ($search !== '') {
            $s = $this->connect()->real_escape_string($search);
            $where = "(cm.message LIKE '%$s%' OR u.full_name LIKE '%$s%' OR cs.guest_id LIKE '%$s%')";
        }
        return $this->select("
            SELECT cs.*,
                   u.full_name AS user_name, u.email AS user_email,
                   (SELECT message FROM chat_messages WHERE session_id=cs.id ORDER BY id DESC LIMIT 1) AS last_msg,
                   (SELECT created_at FROM chat_messages WHERE session_id=cs.id ORDER BY id DESC LIMIT 1) AS last_at,
                   (SELECT COUNT(*) FROM chat_messages WHERE session_id=cs.id AND sender='user') AS msg_count
            FROM chat_sessions cs
            LEFT JOIN users u ON u.id = cs.user_id
            LEFT JOIN chat_messages cm ON cm.session_id = cs.id
            WHERE $where
            GROUP BY cs.id
            ORDER BY cs.updated_at DESC
            LIMIT $limit
        ") ?: [];
    }

    // ── Messages ─────────────────────────────────────────────────────

    public function saveMessage(int $sessionId, string $sender, string $message): int {
        $conn = $this->connect();
        $s    = $conn->real_escape_string($sender);
        $m    = $conn->real_escape_string($message);
        $conn->query("INSERT INTO chat_messages (session_id, sender, message) VALUES ($sessionId, '$s', '$m')");
        $conn->query("UPDATE chat_sessions SET updated_at=NOW() WHERE id=$sessionId");
        return (int)$conn->insert_id;
    }

    public function getMessages(int $sessionId): array {
        return $this->select("SELECT * FROM chat_messages WHERE session_id=$sessionId ORDER BY id ASC") ?: [];
    }

    public function getMessagesSince(int $sessionId, int $lastId): array {
        return $this->select("SELECT * FROM chat_messages WHERE session_id=$sessionId AND id>$lastId ORDER BY id ASC") ?: [];
    }

    // ── Auto-replies ──────────────────────────────────────────────────

    public function matchReply(string $message): ?string {
        $rules = $this->select("SELECT keyword, reply FROM chat_auto_replies WHERE status=1") ?: [];
        $msg   = mb_strtolower(trim($message));

        foreach ($rules as $rule) {
            $keywords = array_map('trim', explode(',', mb_strtolower($rule['keyword'])));
            foreach ($keywords as $kw) {
                if ($kw !== '' && mb_strpos($msg, $kw) !== false) {
                    return $rule['reply'];
                }
            }
        }
        return null;
    }

    public function getFallbackReply(): string {
        return "I couldn't find an answer to that 🤔\nWould you like to talk to a human agent? Click **Talk to Human** below, or WhatsApp us directly!";
    }

    // ── CRUD auto-replies ─────────────────────────────────────────────

    public function getAllReplies(): array {
        return $this->select("SELECT * FROM chat_auto_replies ORDER BY id DESC") ?: [];
    }

    public function saveReply(int $id, string $keyword, string $reply, int $status): void {
        $conn = $this->connect();
        $k    = $conn->real_escape_string($keyword);
        $r    = $conn->real_escape_string($reply);
        if ($id > 0) {
            $conn->query("UPDATE chat_auto_replies SET keyword='$k', reply='$r', status=$status WHERE id=$id");
        } else {
            $conn->query("INSERT INTO chat_auto_replies (keyword, reply, status) VALUES ('$k', '$r', $status)");
        }
    }

    public function deleteReply(int $id): void {
        $this->connect()->query("DELETE FROM chat_auto_replies WHERE id=$id");
    }

    public function toggleReply(int $id): void {
        $this->connect()->query("UPDATE chat_auto_replies SET status = 1-status WHERE id=$id");
    }
}
