<?php
// --- GENERAL UTILITY HELPERS ---

// Get base URL of the application
function base_url($path = '') {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    $base = $scheme . '://' . $host . $scriptDir;
    return rtrim($base, '/\\') . '/' . ltrim($path, '/\\');
}

// Escape HTML content for safety
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// CSRF protection helpers for state-changing forms.
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf_token($token) {
    return is_string($token) && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function require_csrf() {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return;
    }

    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        return;
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'csrf']);
        exit;
    }

    header('Location: index.php?page=home&error=invalid_request');
    exit;
}

// Format number to Rupiah currency
function format_rupiah($amount) {
    return 'Rp' . number_format((float) $amount, 0, ',', '.');
}

// Get user-friendly label for roles
function role_label($role) {
    $labels = ['buyer' => 'Pembeli', 'provider' => 'Penyedia', 'admin' => 'Admin'];
    return $labels[$role] ?? $role;
}

// Get order status label and badge class
function order_status_info($status) {
    $map = [
        'pending' => ['Menunggu', 'pending'],
        'waiting_payment' => ['Menunggu Bayar', 'pending'],
        'paid' => ['Dibayar', 'active'],
        'accepted' => ['Diterima', 'active'],
        'in_progress' => ['Diproses', 'active'],
        'completed' => ['Selesai', 'verified'],
        'cancelled' => ['Dibatalkan', 'inactive'],
    ];
    return $map[$status] ?? ['Unknown', 'pending'];
}

// Get icon class for service category names
function category_icon($name) {
    $icons = [
        'bersih' => 'bi-brush',
        'perbaikan' => 'bi-tools',
        'les' => 'bi-mortarboard',
        'laundry' => 'bi-basket',
        'kecantikan' => 'bi-stars',
        'transport' => 'bi-truck',
    ];
    $lower = strtolower($name);
    foreach ($icons as $key => $icon) {
        if (str_contains($lower, $key)) {
            return $icon;
        }
    }
    return 'bi-grid';
}

// Generate URL slug from string
function slugify($value) {
    $value = strtolower(trim((string) $value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
    return trim($value, '-') ?: 'lainnya';
}
