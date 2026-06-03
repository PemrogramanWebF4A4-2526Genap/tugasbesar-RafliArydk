<?php
$action = $_GET['action'] ?? '';

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

    $stmt = $pdo->prepare('SELECT id, name, email, password, role, is_verified FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

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
    ];

    header('Location: index.php?page=dashboard');
    exit;
}

if ($action === 'logout') {
    unset($_SESSION['user']);
    header('Location: index.php?page=home');
    exit;
}

header('Location: index.php?page=home');
exit;
