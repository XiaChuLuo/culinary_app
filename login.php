<?php
session_start();
include("db_connect.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-wrapper">
  <div class="login-container">
    <h2>Login</h2>
    <form method="POST" action="login.php">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>

