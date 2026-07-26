<?php
date_default_timezone_set('Asia/Colombo');

// PHP 8.1+ changed MySQLi default to throw exceptions for every SQL error.
// Disable that so missing tables / bad queries return false instead of crashing.
mysqli_report(MYSQLI_REPORT_OFF);

class Db {

    protected static $connection;

    // ── CONNECT ─────────────────────────────────────────────────────
    public function connect() {
        if (!isset(self::$connection)) {
            $configFile = defined('ROOT') ? ROOT . '/config.ini' : __DIR__ . '/config.ini';
            $config = parse_ini_file($configFile);

            self::$connection = new mysqli(
                $config['servername'],
                $config['username'],
                $config['password'],
                $config['dbname']
            );

            if (self::$connection->connect_error) {
                http_response_code(500);
                die(json_encode(['success'=>false,'message'=>'Database connection failed.']));
            }

            self::$connection->set_charset('utf8mb4');
        }
        return self::$connection;
    }

    // ── QUERY ────────────────────────────────────────────────────────
    public function query($query) {
        $result = $this->connect()->query($query);
        if ($result === false) {
            error_log('DB Query Error: ' . $this->connect()->error . ' | Query: ' . $query);
        }
        return $result;
    }

    // ── SELECT ALL ───────────────────────────────────────────────────
    public function select($query) {
        $rows   = [];
        $result = $this->query($query);
        if ($result === false || $result === true) return [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->free();
        return $rows;
    }

    // ── SELECT ONE ───────────────────────────────────────────────────
    public function selectOne($query) {
        $result = $this->select($query);
        return ($result && count($result)) ? $result[0] : null;
    }

    // ── INSERT ───────────────────────────────────────────────────────
    public function insert($table, $data) {
        $columns = [];
        $values  = [];

        foreach ($data as $col => $val) {
            $columns[] = "`$col`";
            $values[]  = $this->escape($val);
        }

        $sql = "INSERT INTO `$table` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
        $result = $this->query($sql);
        if ($result === false) return false;
        return $this->connect()->insert_id;
    }

    // ── UPDATE ───────────────────────────────────────────────────────
    public function update($table, $data, $where) {
        $set = [];
        foreach ($data as $col => $val) {
            $set[] = "`$col` = " . $this->escape($val);
        }
        $sql = "UPDATE `$table` SET " . implode(', ', $set) . " WHERE $where";
        return $this->query($sql);
    }

    // ── DELETE ───────────────────────────────────────────────────────
    public function delete($table, $where) {
        return $this->query("DELETE FROM `$table` WHERE $where");
    }

    // ── ESCAPE (handles NULL, bool, int, string) ─────────────────────
    public function escape($value) {
        if ($value === null)          return 'NULL';
        if ($value === true)          return '1';
        if ($value === false)         return '0';
        if (is_int($value))           return (string)$value;
        if (is_float($value))         return (string)$value;
        return "'" . $this->connect()->real_escape_string((string)$value) . "'";
    }

    // ── QUOTE (alias for escape - backward compat) ───────────────────
    public function quote($value) {
        return $this->escape($value);
    }

    // ── ERROR ────────────────────────────────────────────────────────
    public function error() {
        return $this->connect()->error;
    }

    // ── LAST INSERT ID ───────────────────────────────────────────────
    public function lastInsertId() {
        return $this->connect()->insert_id;
    }

    // ── ADMIN ACTIVITY LOG ───────────────────────────────────────────
    public function logActivity($admin_id, $action_type, $table_name, $record_id, $old_data = null, $new_data = null) {
        $old_json = $old_data ? $this->connect()->real_escape_string(json_encode($old_data, JSON_UNESCAPED_UNICODE)) : null;
        $new_json = $new_data ? $this->connect()->real_escape_string(json_encode($new_data, JSON_UNESCAPED_UNICODE)) : null;

        return $this->insert('admin_activity_log', [
            'admin_id'    => $admin_id,
            'action_type' => $action_type,
            'table_name'  => $table_name,
            'record_id'   => $record_id,
            'old_data'    => $old_json,
            'new_data'    => $new_json,
            'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? null,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    // ── USER LOGIN LOG ───────────────────────────────────────────────
    private function getUserIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))       return $_SERVER['HTTP_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    public function logLoginActivity($user_id) {
        $this->insert('user_login_activity', [
            'user_id'    => $user_id,
            'ip_address' => $this->getUserIP(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'login_at'   => date('Y-m-d H:i:s'),
        ]);
    }
}
