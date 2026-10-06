<?php

session_start();
require_once __DIR__ . '/includes/functions.php';

// Already logged in — go to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors      = [];
$globalError = '';
$oldEmail    = '';
$successMsg  = '';

// Show banner after a successful registration redirect
if (($_GET['registered'] ?? '') === '1') {
    $successMsg = 'Registration successful! You can now log in.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $result      = validateLogin($_POST);
    $errors      = $result['errors'];
    $oldEmail    = $result['clean']['email'];

    if (empty($errors)) {
        $email    = trim($_POST['email']);
        $password = trim($_POST['password']);
        $users    = loadUsers();

        // Find the user by email (case-insensitive)
        $found = null;
        foreach ($users as $u) {
            if (strtolower($u['email']) === strtolower($email)) {
                $found = $u;
                break;
            }
        }

        if ($found && password_verify($password, $found['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']    = $found['id'];
            $_SESSION['user_name']  = $found['fullname'];
            $_SESSION['user_email'] = $found['email'];
            header('Location: dashboard.php');
            exit;
        }

        // Generic error 
        $globalError = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Be Money Wise</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

<div class="split">

    <!-- Left panel -->
    <aside class="panel-left">

        <?php if (file_exists(__DIR__ . '/assets/images/logo.png')): ?>
            <img src="assets/images/logo.png" alt="Be Money Wise" class="logo">
        <?php else: ?>
            <div class="logo-placeholder">Logo</div>
        <?php endif; ?>

        <div class="panel-body">
            <h1 class="heading">Plan. Track.<br>Understand.</h1>
            <p class="tagline">Make your allowance last.</p>

            <ul class="features">
                <li>
                    <span class="icon-box"><i data-lucide="wallet"></i></span>
                    Track every peso you spend
                </li>
                <li>
                    <span class="icon-box"><i data-lucide="chart-candlestick"></i></span>
                    See where your money goes
                </li>
                <li>
                    <span class="icon-box"><i data-lucide="piggy-bank"></i></span>
                    Build saving goals that stick
                </li>
            </ul>

            <p class="brand">Be Money <span>Wise</span></p>
        </div>

        <p class="panel-footer">A simple spending awareness tool for students.</p>
    </aside>

    <!-- Right panel -->
    <main class="panel-right">
        <div class="card">

            <nav class="tabs">
                <a href="login.php"    class="tab tab-active"   aria-current="page">Login</a>
                <a href="register.php" class="tab tab-inactive">Register</a>
            </nav>

            <?php if ($successMsg): ?>
                <div class="alert alert-success"><?= htmlspecialchars($successMsg, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if ($globalError): ?>
                <div class="alert alert-error"><?= htmlspecialchars($globalError, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php" novalidate>

                <div class="field">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <input
                            type="email" id="email" name="email"
                            class="input no-icon <?= isset($errors['email']) ? 'is-error' : '' ?>"
                            value="<?= htmlspecialchars($oldEmail, ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="you@example.com"
                            autocomplete="email"
                        >
                    </div>
                    <span class="error-text"><?= isset($errors['email']) ? htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') : '' ?></span>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <input
                            type="password" id="password" name="password"
                            class="input <?= isset($errors['password']) ? 'is-error' : '' ?>"
                            placeholder="••••••••"
                            autocomplete="current-password"
                        >
                        <button type="button" class="eye-btn" aria-label="Show password">
                            <i data-lucide="eye-off"></i>
                        </button>
                    </div>
                    <span class="error-text"><?= isset($errors['password']) ? htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') : '' ?></span>
                </div>

                <button type="submit" class="btn">Login</button>

            </form>

            <p class="switch-link">Don't have an account? <a href="register.php">Register!</a></p>

        </div>
    </main>

</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
