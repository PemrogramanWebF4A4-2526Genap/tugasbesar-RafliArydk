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
