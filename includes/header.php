<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo isset($pageTitle) ? $pageTitle : 'Culinary App'; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/culinary_app/assets/css/style.css">
</head>
<body>
  <div class="page-wrapper">
    <header class="main-header">
      <div class="header-container">
        <a href="/culinary_app/index.php" class="logo">🍳 Culinary App</a>
        <nav class="nav-menu">
          <ul>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
              <li><a href="/culinary_app/competition/view_entries.php">Recipes</a></li>
              <li><a href="/culinary_app/competition/submit_recipe.php">Submit</a></li>
              <li><a href="/culinary_app/competition/results.php">Results</a></li>
            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <li><a href="/culinary_app/competition/admin_panel.php">Admin Panel</a></li>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
              <li><a class="cta-button" href="/culinary_app/logout.php">Logout</a></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </header>
    <main class="main-content">
