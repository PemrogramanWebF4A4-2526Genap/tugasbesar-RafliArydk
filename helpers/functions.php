<?php
function base_url($path = '') {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    $base = $scheme . '://' . $host . $scriptDir;
    return rtrim($base, '/\\') . '/' . ltrim($path, '/\\');
}

function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function format_rupiah($amount) {
    return 'Rp' . number_format((float) $amount, 0, ',', '.');
}

function role_label($role) {
    $labels = ['buyer' => 'Pembeli', 'provider' => 'Penyedia', 'admin' => 'Admin'];
    return $labels[$role] ?? $role;
}

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

function slugify($value) {
    $value = strtolower(trim((string) $value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
    return trim($value, '-') ?: 'lainnya';
}
