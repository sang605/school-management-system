<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit();
}
?>

<h2>Welcome <?php echo $_SESSION['user']; ?></h2>
<p>Your role: <?php echo $_SESSION['role']; ?></p>

<a href="auth/logout.php">Logout</a>