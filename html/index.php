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
    <form method="post" action="login.php" class="login-form">
      <h2>Login</h2>
      <?php if (isset($_GET['error'])): ?>
        <p class="error-message"><?= htmlspecialchars($_GET['error']) ?></p>
      <?php endif; ?>
      
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required />
      </div>
    
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
      </div>
    
      <button type="submit">Log In</button>
    </form>
  </main>

  <footer class="footer">
    <p>&copy; 2025 Scrabble Score. All rights reserved.</p>
  </footer>
</body>
</html>
