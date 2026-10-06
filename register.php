<?php

session_start();
require_once __DIR__ . '/includes/functions.php';

// Already logged in — go to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors  = [];
$oldData = ['fullname' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $result  = validateRegister($_POST);
    $errors  = $result['errors'];
    $oldData = $result['clean'];

    if (empty($errors)) {
        $newUser = [
            'id'            => uniqid('usr_', true),
            'fullname'      => trim($_POST['fullname']),
            'email'         => strtolower(trim($_POST['email'])),
            'password_hash' => password_hash(trim($_POST['password']), PASSWORD_BCRYPT, ['cost' => 12]),
            'registered_at' => date('Y-m-d H:i:s'),
        ];

        $users   = loadUsers();
        $users[] = $newUser;

        if (saveUsers($users)) {
            header('Location: login.php?registered=1');
            exit;
        }

        $errors['_global'] = 'Could not save your registration. Please try again.';
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
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body>

<div class="split">

    <!-- Left branding panel -->
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

    <!-- Right form panel -->
    <main class="panel-right">
        <div class="card">

            <nav class="tabs">
                <a href="login.php"    class="tab tab-inactive">Login</a>
                <a href="register.php" class="tab tab-active"   aria-current="page">Register</a>
            </nav>

            <?php if (isset($errors['_global'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($errors['_global'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php" novalidate>

                <div class="field">
                    <label for="fullname">Fullname</label>
                    <div class="input-wrap">
                        <input
                            type="text" id="fullname" name="fullname"
                            class="input no-icon <?= isset($errors['fullname']) ? 'is-error' : '' ?>"
                            value="<?= htmlspecialchars($oldData['fullname'], ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="Juan dela Cruz"
                            autocomplete="name"
                        >
                    </div>
                    <span class="error-text"><?= isset($errors['fullname']) ? htmlspecialchars($errors['fullname'], ENT_QUOTES, 'UTF-8') : '' ?></span>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <input
                            type="email" id="email" name="email"
                            class="input no-icon <?= isset($errors['email']) ? 'is-error' : '' ?>"
                            value="<?= htmlspecialchars($oldData['email'], ENT_QUOTES, 'UTF-8') ?>"
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
                            autocomplete="new-password"
                        >
                        <button type="button" class="eye-btn" aria-label="Show password">
                            <i data-lucide="eye-off"></i>
                        </button>
                    </div>
                    <span class="error-text"><?= isset($errors['password']) ? htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') : '' ?></span>
                </div>

                <div class="field">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-wrap">
                        <input
                            type="password" id="confirm_password" name="confirm_password"
                            class="input <?= isset($errors['confirm_password']) ? 'is-error' : '' ?>"
                            placeholder="••••••••"
                            autocomplete="new-password"
                        >
                        <button type="button" class="eye-btn" aria-label="Show password">
                            <i data-lucide="eye-off"></i>
                        </button>
                    </div>
                    <span class="error-text"><?= isset($errors['confirm_password']) ? htmlspecialchars($errors['confirm_password'], ENT_QUOTES, 'UTF-8') : '' ?></span>
                </div>

                <button type="submit" class="btn">Register</button>

            </form>

            <p class="switch-link">Already have an account? <a href="login.php">Login!</a></p>

        </div>
    </main>

</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
