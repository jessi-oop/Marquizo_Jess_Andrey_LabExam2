<?php
// helper functions page

define('USERS_FILE', __DIR__ . '/../data/users.json');


// Trim and escape a value for safe output
function clean(string $val): string
{
    return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
}


// Load users from the JSON file; returns [] on any failure
function loadUsers(): array
{
    if (!file_exists(USERS_FILE)) return [];
    $data = json_decode(file_get_contents(USERS_FILE), true);
    return is_array($data) ? $data : [];
}


// Save the users array back to the JSON file
function saveUsers(array $users): bool
{
    return file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}


// Check if an email is already in the users array (case-insensitive)
function emailExists(string $email, array $users): bool
{
    $email = strtolower($email);
    foreach ($users as $u) {
        if (strtolower($u['email']) === $email) return true;
    }
    return false;
}


// registration field validation
function validateRegister(array $post): array
{
    $errors = [];

    $fullname = trim($post['fullname']        ?? '');
    $email    = trim($post['email']           ?? '');
    $password = trim($post['password']        ?? '');
    $confirm  = trim($post['confirm_password'] ?? '');

    if ($fullname === '')
        $errors['fullname'] = 'Full name is required.';
    elseif (!preg_match('/^[A-Za-z\s]+$/', $fullname))
        $errors['fullname'] = 'Full name may only contain letters and spaces.';
    elseif (strlen($fullname) < 2)
        $errors['fullname'] = 'Full name must be at least 2 characters.';

    if ($email === '')
        $errors['email'] = 'Email address is required.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Please enter a valid email address.';
    elseif (emailExists($email, loadUsers()))
        $errors['email'] = 'This email address is already registered.';

    if ($password === '')
        $errors['password'] = 'Password is required.';
    elseif (strlen($password) < 8)
        $errors['password'] = 'Password must be at least 8 characters.';
    elseif (!preg_match('/[A-Z]/', $password))
        $errors['password'] = 'Password must contain at least one uppercase letter.';
    elseif (!preg_match('/[a-z]/', $password))
        $errors['password'] = 'Password must contain at least one lowercase letter.';
    elseif (!preg_match('/[0-9]/', $password))
        $errors['password'] = 'Password must contain at least one number.';

    if ($confirm === '')
        $errors['confirm_password'] = 'Please confirm your password.';
    elseif ($confirm !== $password)
        $errors['confirm_password'] = 'Passwords do not match.';

    return [
        'errors' => $errors,
        'clean'  => ['fullname' => clean($fullname), 'email' => clean($email)],
    ];
}


// validate login page
function validateLogin(array $post): array
{
    $errors = [];

    $email    = trim($post['email']    ?? '');
    $password = trim($post['password'] ?? '');

    if ($email === '')
        $errors['email'] = 'Email address is required.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Please enter a valid email address.';

    if ($password === '')
        $errors['password'] = 'Password is required.';

    return [
        'errors' => $errors,
        'clean'  => ['email' => clean($email)],
    ];
}
