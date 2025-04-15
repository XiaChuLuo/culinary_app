<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$pageTitle = "Dashboard";
?>

<?php include 'includes/header.php'; ?>

<div class="container">
  <div class="welcome-box">
    <h1>Welcome, <?php echo ucfirst($username); ?>!</h1>
    <p>Your role: <strong><?php echo ucfirst($role); ?></strong></p>
  </div>

  <ul class="dashboard-links">
    <?php if ($role === 'user'): ?>
      <li><a class="button-link" href="competition/view_entries.php">View Recipes</a></li>
      <li><a class="button-link" href="competition/results.php">View Results</a></li>
      <li><a class="button-link" href="competition/submit_recipe.php">Submit a Recipe</a></li>
    <?php elseif ($role === 'admin'): ?>
      <li><a class="button-link" href="competition/admin_panel.php">Admin Panel</a></li>
    <?php endif; ?>
    <li><a class="button-link" href="logout.php">Logout</a></li>
  </ul>
</div>

<?php include 'includes/footer.php'; ?>
