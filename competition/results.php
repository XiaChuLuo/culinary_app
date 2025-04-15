<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../db_connect.php';
$pageTitle = "Voting Results";

$sql = "SELECT r.title, COUNT(v.recipe_id) AS total_votes
        FROM recipes r
        LEFT JOIN votes v ON r.id = v.recipe_id
        GROUP BY r.id
        ORDER BY total_votes DESC";

$result = mysqli_query($conn, $sql);
?>

<?php include '../includes/header.php'; ?>

<div class="container">
  <h2>🏆 Voting Results</h2>

  <?php
      $rank = 1;
      $medals = ['🥇', '🥈', '🥉'];
      $highestVotes = null;

      while ($row = mysqli_fetch_assoc($result)):
          if ($highestVotes === null) {
              $highestVotes = $row['total_votes'];
          }

          $isTiedWinner = ($row['total_votes'] == $highestVotes && $highestVotes > 0);
  ?>
    <div class="result-row <?php echo $isWinner ? 'winner-row glowing' : ''; ?>">
        <span class="rank-icon"><?php echo $rank <= 3 ? $medals[$rank - 1] : ''; ?></span>
        <span><?php echo htmlspecialchars($row['title']); ?></span>
        <span class="vote-count">
            <?php echo $row['total_votes']; ?> vote(s)
            <?php if ($isTiedWinner): ?> 🏆 <strong>Winner</strong><?php endif; ?>
        </span>
    </div>
  <?php $rank++; endwhile; ?>
</div>

<?php include '../includes/footer.php'; ?>
