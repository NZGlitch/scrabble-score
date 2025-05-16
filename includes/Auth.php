<?php
require_once __DIR__ . '/db.php';

class Auth {
    
    public static function login(string $email, string $password): bool {
        global $db;

        $stmt = $db->getHandle()->prepare("SELECT * FROM members WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // User not found or password fields are NULL — disallow login
        if (
            !$user ||
            empty($user['password_salt']) ||
            empty($user['password_hash'])
        ) {
            return false;
        }

        $salt = $user['password_salt'];
        $expectedHash = hash('sha256', $salt . '__' . $password);

        if ($expectedHash === $user['password_hash']) {
            $_SESSION['user_id'] = $user['member_number'];
            $_SESSION['first_name'] = $user['first_name'];
            return true;
        }

        return false;
    }

    public static function logout(): void {
        session_destroy();
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }
}
