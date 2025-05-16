<?php
session_start();
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Scrabble Score</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <header class="header">
    <img src="logo.webp" alt="Scrabble Score Logo" class="logo" />
    <h1>Scrabble Score</h1>
  </header>

  <main class="main-content">
    <p>Welcome to Scrabble Score – your tool for tracking Scrabble game scores easily!</p>
    <form method="POST" action="login.php">
      <label>Email:</label><br>
      <input type="email" name="email" required><br>
      <label>Password:</label><br>
      <input type="password" name="password" required><br>
      <button type="submit">Login</button>
    </form>

    <?php if ($error): ?>
      <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
  </main>

  <footer class="footer">
    <p>&copy; 2025 Scrabble Score. All rights reserved.</p>
  </footer>
</body>
</html>
