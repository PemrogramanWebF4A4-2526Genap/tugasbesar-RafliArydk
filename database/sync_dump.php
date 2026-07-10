<?php
/**
 * Sinkron manual: database MySQL -> database/database.sql
 *
 * Cara pakai (Laragon):
 *   php database/sync_dump.php
 *
 * Atau lewat browser (admin saja):
 *   index.php?page=sync_dump
 */

require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/helpers/database_dump.php';

$isCli = PHP_SAPI === 'cli';
$success = sync_bisabantu_sql_dump($pdo, bisabantu_db_config_from_constants());

if ($isCli) {
    echo $success
        ? "Dump berhasil disimpan ke database/database.sql\n"
        : "Dump gagal. Cek error log PHP.\n";
    exit($success ? 0 : 1);
}

header('Content-Type: text/plain; charset=utf-8');
echo $success
    ? "Dump berhasil disimpan ke database/database.sql"
    : "Dump gagal. Cek error log PHP.";
