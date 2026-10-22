<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: auth/login.php');
    exit();
}

$username = htmlspecialchars((string) $_SESSION['user'], ENT_QUOTES, 'UTF-8');
$role = htmlspecialchars((string) ($_SESSION['role'] ?? 'User'), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
</head>
<body>
    <header>
        <h1>School Management System</h1>
        <p>Welcome, <?= $username ?> (<?= $role ?>)</p>
    </header>

    <main>
        <h2>Dashboard</h2>
        <p>Choose a section to get started.</p>
        <nav aria-label="Main navigation">
            <ul>
                <li><a href="modules/students/view.php">Students</a></li>
            </ul>
        </nav>
    </main>

    <footer>
        <a href="auth/logout.php">Log out</a>
    </footer>
</body>
</html>
