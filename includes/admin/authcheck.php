<?php
session_start();
require_once __DIR__ . '/../../includes/Auth.php';

if (!Auth::isLoggedIn()) {
    header('Location: /index.php');
    exit;
}
