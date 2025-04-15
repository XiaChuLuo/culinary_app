<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

include '../db_connect.php';

$userId = $_SESSION['user_id'];
$recipeId = $_POST['recipe_id'] ?? null;

if ($recipeId) {
    // Check if user already voted
    $checkVote = mysqli_query($conn, "SELECT * FROM votes WHERE user_id = $userId");
    
    if (mysqli_num_rows($checkVote) == 0) {
        // Insert vote
        $insert = mysqli_query($conn, "INSERT INTO votes (user_id, recipe_id) VALUES ($userId, $recipeId)");
        
        if ($insert) {
            header("Location: view_entries.php?msg=success");
            exit();
        } else {
            echo "<p style='color:red;'>❌ Error inserting vote.</p>";
        }
    } else {
        echo "<p style='color:red;'>⚠️ You have already voted!</p>";
    }
} else {
    echo "<p style='color:red;'>❌ Invalid request.</p>";
}
?>
