<?php
session_start();
require_once __DIR__ . '/../includes/admin/SessionManager.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (SessionManager::login($email, $password)) {
    header('Location: /admin/');
    exit;
} else {
    header('Location: index.php?error=Invalid login');
    exit;
}
