<?php

session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$name  = htmlspecialchars($_SESSION['user_name']  ?? 'User', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_SESSION['user_email'] ?? '',     ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Be Money Wise</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

<div class="dashboard">
    <div class="dash-card">

        <?php if (file_exists(__DIR__ . '/assets/images/logo.png')): ?>
            <img src="assets/images/logo.png" alt="Be Money Wise" class="logo">
        <?php else: ?>
            <div class="logo-placeholder">BMW</div>
        <?php endif; ?>

        <p class="brand">Be Money <span>Wise</span></p>

        <hr>

        <h1>Welcome, <?= $name ?>!</h1>
        <p class="sub-text">You're logged in to Be Money Wise.</p>
        <p class="sub-text"><?= $email ?></p>

        <hr>

        <a href="logout.php" class="btn-logout">Logout</a>

    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
