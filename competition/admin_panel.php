<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../db_connect.php';
$pageTitle = "Admin Panel";

// Load current voting status
$status_result = mysqli_query($conn, "SELECT is_active FROM voting_status LIMIT 1");
$status_row = mysqli_fetch_assoc($status_result);
$isVotingActive = $status_row['is_active'];

// Handle voting toggle
if (isset($_GET['toggle_vote'])) {
    $newStatus = $_GET['toggle_vote'] === 'end' ? 0 : 1;
    mysqli_query($conn, "UPDATE voting_status SET is_active = $newStatus");
    header("Location: admin_panel.php");
    exit();
}

// Handle recipe cancel
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM recipes WHERE id = $deleteId");
    mysqli_query($conn, "DELETE FROM votes WHERE recipe_id = $deleteId");
    header("Location: admin_panel.php");
    exit();
}

// Load all recipes
$result = mysqli_query($conn, "SELECT recipes.*, users.username FROM recipes JOIN users ON recipes.user_id = users.id");

include '../includes/header.php';
?>

<div class="container">
  <h2>Admin Panel - Manage Recipes</h2>

  <p style="font-weight:bold;">
    Voting is currently:
    <span style="color: <?php echo $isVotingActive ? 'green' : 'red'; ?>;">
      <?php echo $isVotingActive ? 'OPEN' : 'CLOSED'; ?>
    </span>
  </p>

  <?php if ($isVotingActive): ?>
    <a class="button-link" href="?toggle_vote=end">End Voting</a>
  <?php else: ?>
    <a class="button-link" href="?toggle_vote=start">Restart Voting</a>
  <?php endif; ?>

  <br><br>
  <hr><br>

  <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="recipe-card">
      <img src="../competition/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Recipe Image">
      <div class="recipe-card-content">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        <p><em>Submitted by: <?php echo htmlspecialchars($row['username']); ?></em></p>
        <a class="delete-button" href="?delete_id=<?php echo $row['id']; ?>">🗑 Cancel</a>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<?php include '../includes/footer.php'; ?>
