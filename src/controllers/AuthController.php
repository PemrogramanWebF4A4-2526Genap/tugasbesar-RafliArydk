<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/automation.php';

$action = $_GET['action'] ?? '';
$userModel = new UserModel($pdo);

function is_valid_auth_email($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $domain = strtolower(substr(strrchr($email, '@') ?: '', 1));
    return !preg_match('/\.co$/', $domain);
}

/**
 * Checks that the email domain has a real MX record.
 * Uses checkdnsrr with a fallback to A record.
 */
function has_valid_mx_domain($email)
{
    $domain = strtolower(substr(strrchr($email, '@') ?: '', 1));
    if (empty($domain)) {
        return false;
    }
    return checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');
}

/**
 * Generates a cryptographically random 6-digit OTP.
 */
function generate_otp()
{
    return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Sends the OTP email using the existing automation helper.
 */
function send_otp_email($to, $otp)
{
    $subject = 'Kode Verifikasi BisaBantu';
    $message = "Halo,\n\nKode verifikasi BisaBantu Anda adalah:\n\n  {$otp}\n\nKode berlaku selama 15 menit. Jangan bagikan kode ini kepada siapapun.\n\nSalam,\nTim BisaBantu";
    return send_automation_email($to, $subject, $message);
}

// ---------------------------------------------------------------------------
// ACTION: login
// ---------------------------------------------------------------------------
if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=home&auth=login');
        exit;
    }

    $email    = trim($_POST['email'] ?? '');
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
        'id'           => (int) $user['id'],
        'name'         => $user['name'],
        'email'        => $user['email'],
        'role'         => $user['role'],
        'is_verified'  => (int) $user['is_verified'],
        'phone'        => $user['phone'] ?? '',
        'address'      => $user['address'] ?? '',
        'profile_photo' => $user['profile_photo'] ?? '',
    ];

    header('Location: index.php?page=home');
    exit;
}

// ---------------------------------------------------------------------------
// ACTION: register  — validate data, check domain MX, store in session, send OTP
// ---------------------------------------------------------------------------
if ($action === 'register') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=home&auth=register');
        exit;
    }

    $role          = $_POST['role'] ?? 'buyer';
    $firstName     = trim($_POST['first_name'] ?? '');
    $lastName      = trim($_POST['last_name'] ?? '');
    $name          = trim($firstName . ' ' . $lastName);
    $email         = trim($_POST['email'] ?? '');
    $password      = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $phone         = trim($_POST['phone'] ?? '');
    $address       = trim($_POST['address'] ?? '');

    $preserveValues = http_build_query([
        'role'          => $role,
        'first_name'    => $firstName,
        'last_name'     => $lastName,
        'email'         => $email,
        'phone'         => $phone,
        'address'       => $address,
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

    // Check if email domain can actually receive mail
    if (!has_valid_mx_domain($email)) {
        header('Location: index.php?page=home&auth=register&register_error=invalid_domain&' . $preserveValues);
        exit;
    }

    // Check if email already exists before sending OTP
    if ($userModel->getAuthUserByEmail($email)) {
        header('Location: index.php?page=home&auth=register&register_error=exists&' . $preserveValues);
        exit;
    }

    // Generate OTP and store pending registration in session
    $otp = generate_otp();
    $_SESSION['pending_reg'] = [
        'name'       => $name,
        'email'      => $email,
        'password'   => password_hash($password, PASSWORD_BCRYPT),
        'role'       => $role,
        'phone'      => $phone,
        'address'    => $address,
        'otp'        => $otp,
        'expires_at' => time() + 900, // 15 minutes
    ];

    send_otp_email($email, $otp);

    header('Location: index.php?page=home&auth=verify&pending_email=' . urlencode($email));
    exit;
}

// ---------------------------------------------------------------------------
// ACTION: verify_email  — validate OTP, create user account
// ---------------------------------------------------------------------------
if ($action === 'verify_email') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?page=home&auth=verify');
        exit;
    }

    $pending = $_SESSION['pending_reg'] ?? null;

    if (!$pending) {
        header('Location: index.php?page=home&auth=verify&verify_error=no_pending');
        exit;
    }

    if (time() > $pending['expires_at']) {
        unset($_SESSION['pending_reg']);
        header('Location: index.php?page=home&auth=verify&verify_error=expired');
        exit;
    }

    $submittedOtp = trim($_POST['otp_code'] ?? '');

    if ($submittedOtp !== $pending['otp']) {
        $email = urlencode($pending['email']);
        header("Location: index.php?page=home&auth=verify&verify_error=invalid&pending_email={$email}");
        exit;
    }

    // OTP is correct — create the user account
    $isVerified = $pending['role'] === 'buyer' ? 1 : 0;

    try {
        $userModel->createUser(
            $pending['name'],
            $pending['email'],
            $pending['password'],
            $pending['role'],
            $isVerified,
            $pending['phone'],
            $pending['address']
        );
        bisabantu_sync_sql_dump_after_write($pdo);
    } catch (PDOException $e) {
        // Email may have been registered by another request in the meantime
        unset($_SESSION['pending_reg']);
        header('Location: index.php?page=home&auth=register&register_error=exists');
        exit;
    }

    unset($_SESSION['pending_reg']);

    $registerParam = $pending['role'] === 'provider' ? 'success_provider' : 'success';
    header('Location: index.php?page=home&auth=login&register=' . $registerParam);
    exit;
}

// ---------------------------------------------------------------------------
// ACTION: resend_otp  — regenerate OTP and re-send email
// ---------------------------------------------------------------------------
if ($action === 'resend_otp') {
    $pending = $_SESSION['pending_reg'] ?? null;

    if (!$pending) {
        header('Location: index.php?page=home&auth=register');
        exit;
    }

    $otp = generate_otp();
    $_SESSION['pending_reg']['otp']        = $otp;
    $_SESSION['pending_reg']['expires_at'] = time() + 900;

    send_otp_email($pending['email'], $otp);

    header('Location: index.php?page=home&auth=verify&pending_email=' . urlencode($pending['email']) . '&resent=1');
    exit;
}

// ---------------------------------------------------------------------------
// ACTION: logout
// ---------------------------------------------------------------------------
if ($action === 'logout') {
    unset($_SESSION['user']);
    header('Location: index.php?page=home');
    exit;
}

header('Location: index.php?page=home');
exit;

