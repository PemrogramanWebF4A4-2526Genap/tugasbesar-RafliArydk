<?php
// --- CONFIG: DATABASE CONNECTION ---
define('DB_HOST', getenv('BISABANTU_DB_HOST') ?: getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('BISABANTU_DB_PORT') ?: getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('BISABANTU_DB_NAME') ?: getenv('DB_NAME') ?: 'bisabantu');
define('DB_USER', getenv('BISABANTU_DB_USER') ?: getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('BISABANTU_DB_PASS') ?: getenv('DB_PASS') ?: '');

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
    DB_HOST,
    DB_PORT,
    DB_NAME
);

// Initialize connection
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}
?>