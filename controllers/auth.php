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

    $emailCandidates = [$email];
    if (substr($email, -13) === '@bisabantu.co') {
        $emailCandidates[] = substr($email, 0, -2) . 'com';
    }

    $placeholders = implode(',', array_fill(0, count($emailCandidates), '?'));
    $stmt = $pdo->prepare("SELECT id, name, email, password, role, is_verified FROM users WHERE email IN ($placeholders) LIMIT 1");
    $stmt->execute($emailCandidates);
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

    $hashed = password_hash($password, PASSWORD_BCRYPT);
    $isVerified = $role === 'buyer' ? 1 : 0;

    try {
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, is_verified, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$name, $email, $hashed, $role, $isVerified, $phone, $address]);
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

        if ($name && $email && $password) {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            try {
                $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, is_verified, phone, address) VALUES (?, ?, ?, "buyer", 1, ?, ?)');
                $stmt->execute([$name, $email, $hashed, $phone, $address]);
                // Auto login or redirect to login
                header('Location: index.php?page=home&auth=login&register=success');
                exit;
            } catch (PDOException $e) {
                // Email might exist
                header('Location: index.php?page=auth&action=register_buyer&error=exists');
                exit;
            }
        }
    } else {
        // Show view
        include 'views/auth/register_pembeli.php';
        exit;
    }
}

if ($action === 'register_provider') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($name && $email && $password) {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            try {
                // Provider is_verified = 0 by default
                $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, is_verified, phone, address) VALUES (?, ?, ?, "provider", 0, ?, ?)');
                $stmt->execute([$name, $email, $hashed, $phone, $address]);
                header('Location: index.php?page=home&auth=login&register=success_provider');
                exit;
            } catch (PDOException $e) {
                header('Location: index.php?page=auth&action=register_provider&error=exists');
                exit;
            }
        }
    } else {
        // Show view
        include 'views/auth/register_penyedia.php';
        exit;
    }
}

if ($action === 'logout') {
    unset($_SESSION['user']);
    header('Location: index.php?page=home');
    exit;
}

header('Location: index.php?page=home');
exit;
