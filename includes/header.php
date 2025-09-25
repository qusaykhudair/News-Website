<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Global News Network</title>
  <link rel="stylesheet" href="styles.css" />
</head>

<body>

  <header class="main-header">
    <div class="top-bar">
      <div class="container">
        <div class="top-left">
          <span class="date"><?php echo date('l, F j, Y'); ?></span>
        </div>
        <div class="top-right">

          <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <a class="adminBtn" href="admin/dashboard.php">Admin Page</a>
            <?php endif; ?>
            <a class="logoutBtn" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
          <?php else: ?>
            <a class="btn btn-primary " href="register.php">Sign Up</a>
            <a class="btn btn-outline" href="login.php">Login</a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="main-nav-bar">
      <div class="container">
        <div class="logo">
          <h1><a href="index.php"> Global News</a></h1>
        </div>
        <div class="theme-switch-wrapper">
          <label class="theme-switch" for="themeToggle">
            <input type="checkbox" id="themeToggle">
            <span class="slider round"></span>
          </label>
        </div>

        <nav class="main-nav">
          <ul class="nav-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="category.php?id=1">Politics</a></li>
            <li><a href="category.php?id=2">Technology</a></li>
            <li><a href="category.php?id=3">Sports</a></li>
            <li><a href="category.php?id=4">World</a></li>
            <li><a href="../about.php">About</a></li>
            <li><a href="../contact.php">Contact</a></li>
          </ul>
        </nav>
        <form class="search-form" action="search.php" method="GET">
          <input type="text" name="q" placeholder="Search news..." required>
          <button type="submit">Search</button>
        </form>
      </div>
    </div>
    <script src="/project2/project/Theme/theme.js"></script>
  </header>