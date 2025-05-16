<?php
require_once 'authcheck.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['first_name']) ?>!</h1>
    <a href="logout.php">Logout</a>
</body>
</html>
