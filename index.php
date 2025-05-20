<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Кіру</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Жүйеге кіру</h2>
    <?php if (isset($_SESSION['error'])): ?>
      <p style="color:red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form action="login_process.php" method="POST">
      <input type="text" name="username" placeholder="Логин" required>
      <input type="password" name="password" placeholder="Құпиясөз" required>
      <button type="submit">Кіру</button>
    </form>
  </div>
</body>
</html>