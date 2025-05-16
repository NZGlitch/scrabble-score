<?php
session_start();
require_once __DIR__ . '/../includes/Auth.php';

if (!Auth::isLoggedIn()) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['first_name']) ?>!</h1>
    <a href="logout.php">Logout</a>
</body>
</html>
