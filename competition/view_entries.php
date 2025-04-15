<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../db_connect.php';
$pageTitle = "View Recipes";

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Get recipes
$result = mysqli_query($conn, "SELECT recipes.*, users.username FROM recipes JOIN users ON recipes.user_id = users.id");

// Voting status
$votingStatus = mysqli_query($conn, "SELECT is_active FROM voting_status LIMIT 1");
$voting = mysqli_fetch_assoc($votingStatus)['is_active'];

// Check if user has voted
$voteQuery = mysqli_query($conn, "SELECT recipe_id FROM votes WHERE user_id = $userId");
$hasVoted = mysqli_num_rows($voteQuery) > 0;
$votedRecipeId = $hasVoted ? mysqli_fetch_assoc($voteQuery)['recipe_id'] : null;

include '../includes/header.php';
?>

<h3>All Submitted Recipes</h3>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
  <div class="recipe-card">
    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Recipe Image">
    <div class="recipe-card-content">
      <h3><?php echo htmlspecialchars($row['title']); ?></h3>
      <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
      <p><em>Submitted by: <?php echo htmlspecialchars($row['username']); ?></em></p>

      <?php if ($role === 'user' && $voting): ?>
        <?php if (!$hasVoted): ?>
          <form method="POST" action="vote.php">
            <input type="hidden" name="recipe_id" value="<?php echo $row['id']; ?>">
            <button type="submit">Vote for This Recipe</button>
          </form>
        <?php elseif ($votedRecipeId == $row['id']): ?>
          <form method="POST" action="unvote.php">
            <button class="delete-button" type="submit">Unvote</button>
          </form>
        <?php else: ?>
          <p class="success-message">You have voted for another recipe.</p>
        <?php endif; ?>
      <?php elseif (!$voting): ?>
        <p class="error-message">Voting is currently closed.</p>
      <?php endif; ?>
    </div>
  </div>
<?php endwhile; ?>

<?php include '../includes/footer.php'; ?>
