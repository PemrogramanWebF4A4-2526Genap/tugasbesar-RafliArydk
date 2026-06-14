<?php
$host = 'localhost';
$dbname = 'bisabantu'; // ganti dengan nama DB Anda
$username = 'root';
$password = '';

define('DB_HOST', $host);
define('DB_NAME', $dbname);
define('DB_USER', $username);
define('DB_PASS', $password);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>