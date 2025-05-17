<?php

if (session_status() === PHP_SESSION_NONE) session_start();

class SessionManager {
    private $db;
    private $memberNumber;

    public function __construct($memberNumber) {
        $this->db = $GLOBALS['db']->getHandle();
        $this->memberNumber = $memberNumber;
    }

    private function generateApiKey() {
        return bin2hex(random_bytes(32)); // 64-character hex string
    }

    private function getExpirationTime() {
        return (new DateTime('+10 minutes'))->format('Y-m-d H:i:s');
    }

    public function createApiKey() {
        $apiKey = $this->generateApiKey();
        $createdAt = (new DateTime())->format('Y-m-d H:i:s');
        $expiresAt = $this->getExpirationTime();

        $stmt = $this->db->prepare('INSERT INTO api_keys (member_number, api_key, created_at, expires_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([$this->memberNumber, $apiKey, $createdAt, $expiresAt]);

        $_SESSION['api_key'] = $apiKey;
        $_SESSION['last_active'] = time();
        return $apiKey;
    }

    public function getValidApiKey() {
        // Check existing key
        $stmt = $this->db->prepare('SELECT * FROM api_keys WHERE member_number = ? ORDER BY created_at DESC LIMIT 1');
        $stmt->execute([$this->memberNumber]);
        $key = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($key) {
            $now = new DateTime();
            $expiresAt = new DateTime($key['expires_at']);
            $createdAt = new DateTime($key['created_at']);

            // Key expired or over 10 minutes old (rotate)
            if ($now > $expiresAt || $now->getTimestamp() - $createdAt->getTimestamp() >= 600) {
                return $this->createApiKey(); // rotate key
            }

            // Refresh session timeout
            $_SESSION['last_active'] = time();
            $_SESSION['api_key'] = $key['api_key'];
            return $key['api_key'];
        }

        // No key found, create a new one
        return $this->createApiKey();
    }

    public static function isSessionExpired() {
        if (!isset($_SESSION['last_active'])) return true;
        return (time() - $_SESSION['last_active']) > 600; // 10 minutes
    }

    public static function destroySession() {
        session_unset();
        session_destroy();
    }
}
