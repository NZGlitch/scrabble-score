<?php
session_start();
require_once __DIR__ . '/../includes/Auth.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (Auth::login($email, $password)) {
    header('Location: admin/admin.php');
    exit;
} else {
    header('Location: index.php?error=Invalid login');
    exit;
}
