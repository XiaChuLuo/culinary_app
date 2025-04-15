<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

include '../db_connect.php';
$pageTitle = "Submit a Recipe";

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $userId = $_SESSION['user_id'];

    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $sql = "INSERT INTO recipes (title, ingredients, description, image, user_id)
            VALUES ('$title', '$ingredients', '$description', '$image', $userId)";
    if (mysqli_query($conn, $sql)) {
        $message = "Recipe submitted successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

include '../includes/header.php';
?>

<div class="container">
  <h2>Submit Your Recipe</h2>

  <?php if ($message): ?>
    <p class="<?php echo strpos($message, 'success') !== false ? 'success-message' : 'error-message'; ?>">
      <?php echo $message; ?>
    </p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Recipe Title" required>
    <textarea name="ingredients" placeholder="Ingredients (e.g., 2 eggs, 1 cup flour)" rows="3" required></textarea>
    <textarea name="description" placeholder="Preparation Steps" rows="4" required></textarea>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit">Submit Recipe</button>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
