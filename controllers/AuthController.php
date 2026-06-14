<?php
require_once __DIR__ . '/../models/UserModel.php';

$action = $_GET['action'] ?? '';
$userModel = new UserModel($pdo);

function is_valid_auth_email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $domain = strtolower(substr(strrchr($email, '@') ?: '', 1));
    return !preg_match('/\.co$/', $domain);
}

if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=home&auth=login');
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        header('Location: index.php?page=home&auth=login&auth_error=empty');
        exit;
    }

    if (!is_valid_auth_email($email)) {
        header('Location: index.php?page=home&auth=login&auth_error=email');
        exit;
    }

    $user = $userModel->getAuthUserByEmail($email);

    if (!$user || !password_verify($password, $user['password'])) {
        header('Location: index.php?page=home&auth=login&auth_error=invalid');
        exit;
    }

    session_regenerate_id(true);

    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
        'is_verified' => (int) $user['is_verified'],
        'phone' => $user['phone'] ?? '',
        'address' => $user['address'] ?? '',
        'profile_photo' => $user['profile_photo'] ?? '',
    ];

    header('Location: index.php?page=dashboard');
    exit;
}

if ($action === 'register') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=home&auth=register');
        exit;
    }

    $role = $_POST['role'] ?? 'buyer';
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $name = trim($firstName . ' ' . $lastName);
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $preserveValues = http_build_query([
        'role' => $role,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'register_step' => 3,
    ]);

    if (!in_array($role, ['buyer', 'provider'], true)) {
        header('Location: index.php?page=home&auth=register&register_error=role&' . $preserveValues);
        exit;
    }

    if ($name === '' || $email === '' || $password === '' || strlen($password) < 8 || $password !== $passwordConfirm) {
        header('Location: index.php?page=home&auth=register&register_error=invalid&' . $preserveValues);
        exit;
    }

    if (!is_valid_auth_email($email)) {
        header('Location: index.php?page=home&auth=register&register_error=email&' . $preserveValues);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $isVerified = $role === 'buyer' ? 1 : 0;

    try {
        $userModel->createUser($name, $email, $hashed, $role, $isVerified, $phone, $address);
        bisabantu_sync_sql_dump_after_write($pdo);
        $registerParam = $role === 'provider' ? 'success_provider' : 'success';
        header('Location: index.php?page=home&auth=login&register=' . $registerParam);
        exit;
    } catch (PDOException $e) {
        header('Location: index.php?page=home&auth=register&register_error=exists&' . $preserveValues);
        exit;
    }
}

if ($action === 'register_buyer') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($name && $email && $password && is_valid_auth_email($email)) {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            try {
                $userModel->createUser($name, $email, $hashed, 'buyer', 1, $phone, $address);
                bisabantu_sync_sql_dump_after_write($pdo);
                header('Location: index.php?page=home&auth=login&register=success');
                exit;
            } catch (PDOException $e) {
                header('Location: index.php?page=auth&action=register_buyer&error=exists');
                exit;
            }
        }

        header('Location: index.php?page=auth&action=register_buyer&error=email');
        exit;
    }

    include 'views/auth/register_pembeli.php';
    exit;
}

if ($action === 'register_provider') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($name && $email && $password && is_valid_auth_email($email)) {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            try {
                $userModel->createUser($name, $email, $hashed, 'provider', 0, $phone, $address);
                bisabantu_sync_sql_dump_after_write($pdo);
                header('Location: index.php?page=home&auth=login&register=success_provider');
                exit;
            } catch (PDOException $e) {
                header('Location: index.php?page=auth&action=register_provider&error=exists');
                exit;
            }
        }

        header('Location: index.php?page=auth&action=register_provider&error=email');
        exit;
    }

    include 'views/auth/register_penyedia.php';
    exit;
}

if ($action === 'logout') {
    unset($_SESSION['user']);
    header('Location: index.php?page=home');
    exit;
}

header('Location: index.php?page=home');
exit;
