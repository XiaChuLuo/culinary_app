<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../db_connect.php';

$userId = $_SESSION['user_id'];

// Delete the user's vote
mysqli_query($conn, "DELETE FROM votes WHERE user_id = $userId");

header("Location: view_entries.php");
exit();
