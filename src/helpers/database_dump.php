<?php

/**
 * Menyinkronkan isi database MySQL ke file database/database.sql.
 * Dipanggil setelah operasi tulis (register, CRUD jasa, dll.) agar dump
 * selalu mencerminkan data terbaru di phpMyAdmin maupun saat import ulang.
 */

function bisabantu_dump_file_path(): string
{
    return dirname(__DIR__, 2) . '/database/database.sql';
}

function bisabantu_dump_table_order(): array
{
    return [
        'users',
        'categories',
        'services',
        'orders',
        'order_items',
        'payments',
        'reviews',
        'notifications',
        'invoices',
        'provider_schedules',
    ];
}

function bisabantu_dump_table_labels(): array
{
    return [
        'users' => 'users',
        'categories' => 'Kategori',
        'services' => 'Jasa (services)',
        'orders' => 'Orders',
        'order_items' => 'Order items',
        'payments' => 'Payments',
        'reviews' => 'Reviews',
        'notifications' => 'Notifications',
        'invoices' => 'Invoices',
        'provider_schedules' => 'Provider schedules',
    ];
}

function bisabantu_sql_value(PDO $pdo, $value): string
{
    if ($value === null) {
        return 'NULL';
    }

    return $pdo->quote((string) $value);
}

function bisabantu_build_insert_block(PDO $pdo, string $table, array $rows): string
{
    if ($rows === []) {
        return '';
    }

    $columns = array_keys($rows[0]);
    $columnList = '`' . implode('`, `', $columns) . '`';
    $valueRows = [];

    foreach ($rows as $row) {
        $values = [];
        foreach ($columns as $column) {
            $values[] = bisabantu_sql_value($pdo, $row[$column] ?? null);
        }
        $valueRows[] = '(' . implode(', ', $values) . ')';
    }

    return "INSERT INTO `$table` ($columnList) VALUES\n"
        . implode(",\n", $valueRows)
        . ";\n";
}

function bisabantu_export_sql_via_pdo(PDO $pdo, string $databaseName): string
{
    $lines = [];
    $lines[] = '-- ======================================================';
    $lines[] = '-- Database: ' . $databaseName;
    $lines[] = '-- BisaBantu (Lokal Service Marketplace)';
    $lines[] = '-- PHP Native + Bootstrap 5';
    $lines[] = '-- Dump otomatis: ' . date('Y-m-d H:i:s');
    $lines[] = '-- ======================================================';
    $lines[] = '';
    $lines[] = 'CREATE DATABASE IF NOT EXISTS `' . $databaseName . '`';
    $lines[] = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;';
    $lines[] = '';
    $lines[] = 'USE `' . $databaseName . '`;';
    $lines[] = '';
    $lines[] = 'SET FOREIGN_KEY_CHECKS=0;';
    $lines[] = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";';
    $lines[] = 'SET time_zone = "+00:00";';
    $lines[] = '';

    $existingTables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    $orderedTables = bisabantu_dump_table_order();

    foreach ($existingTables as $table) {
        if (!in_array($table, $orderedTables, true)) {
            $orderedTables[] = $table;
        }
    }

    $tableNumber = 1;
    foreach ($orderedTables as $table) {
        if (!in_array($table, $existingTables, true)) {
            continue;
        }

        $createStmt = $pdo->query('SHOW CREATE TABLE `' . str_replace('`', '``', $table) . '`')->fetch(PDO::FETCH_ASSOC);
        if (!$createStmt || empty($createStmt['Create Table'])) {
            continue;
        }

        $lines[] = '-- --------------------------------------------------------';
        $lines[] = '-- Tabel ' . $tableNumber . ': ' . $table;
        $lines[] = '-- --------------------------------------------------------';
        $lines[] = 'DROP TABLE IF EXISTS `' . $table . '`;';
        $lines[] = $createStmt['Create Table'] . ';';
        $lines[] = '';
        $tableNumber++;
    }

    $lines[] = '-- ======================================================';
    $lines[] = '-- DATA (sinkron dari MySQL)';
    $lines[] = '-- ======================================================';
    $lines[] = '';

    foreach ($orderedTables as $table) {
        if (!in_array($table, $existingTables, true)) {
            continue;
        }

        $rows = $pdo->query('SELECT * FROM `' . str_replace('`', '``', $table) . '`')->fetchAll(PDO::FETCH_ASSOC);
        if ($rows === []) {
            continue;
        }

        $label = bisabantu_dump_table_labels()[$table] ?? $table;
        $lines[] = '-- ' . $label;
        $lines[] = bisabantu_build_insert_block($pdo, $table, $rows);
        $lines[] = '';
    }

    $lines[] = 'SET FOREIGN_KEY_CHECKS=1;';
    $lines[] = '';
    $lines[] = '-- ======================================================';
    $lines[] = '-- Selesai';
    $lines[] = '-- ======================================================';
    $lines[] = '';

    return implode("\n", $lines);
}

function bisabantu_find_mysqldump_binary(): ?string
{
    $candidates = [];

    if (PHP_OS_FAMILY === 'Windows') {
        $laragonMysql = glob('C:/laragon/bin/mysql/mysql-*/bin/mysqldump.exe') ?: [];
        $candidates = array_merge($laragonMysql, [
            'C:/laragon/bin/mysql/mysql-8.4.3-winx64/bin/mysqldump.exe',
            'C:/xampp/mysql/bin/mysqldump.exe',
        ]);
    } else {
        $candidates = ['/usr/bin/mysqldump', '/usr/local/bin/mysqldump', 'mysqldump'];
    }

    foreach ($candidates as $binary) {
        if ($binary !== 'mysqldump' && !is_file($binary)) {
            continue;
        }

        $command = escapeshellarg($binary) . ' --version';
        @exec($command, $output, $exitCode);
        if ($exitCode === 0) {
            return $binary;
        }
    }

    return null;
}

function bisabantu_export_sql_via_mysqldump(array $dbConfig): ?string
{
    if (!function_exists('exec')) {
        return null;
    }

    $binary = bisabantu_find_mysqldump_binary();
    if ($binary === null) {
        return null;
    }

    $host = $dbConfig['host'] ?? 'localhost';
    $user = $dbConfig['username'] ?? 'root';
    $password = $dbConfig['password'] ?? '';
    $database = $dbConfig['dbname'] ?? 'bisabantu';

    $commandParts = [
        escapeshellarg($binary),
        '--host=' . escapeshellarg($host),
        '--user=' . escapeshellarg($user),
        '--default-character-set=utf8mb4',
        '--routines',
        '--triggers',
        '--single-transaction',
        '--add-drop-table',
        '--databases',
        '--comments',
    ];

    if ($password === '') {
        $commandParts[] = '--password=';
    } else {
        $commandParts[] = '--password=' . escapeshellarg($password);
    }

    $commandParts[] = escapeshellarg($database);

    $output = [];
    $exitCode = 1;
    @exec(implode(' ', $commandParts), $output, $exitCode);

    if ($exitCode !== 0 || $output === []) {
        return null;
    }

    $dump = implode("\n", $output);
    $header = "-- ======================================================\n"
        . "-- Database: {$database}\n"
        . "-- BisaBantu (Lokal Service Marketplace)\n"
        . "-- Dump otomatis (mysqldump): " . date('Y-m-d H:i:s') . "\n"
        . "-- ======================================================\n\n";

    return $header . $dump . "\n";
}

function sync_bisabantu_sql_dump(PDO $pdo, array $dbConfig = []): bool
{
    try {
        $databaseName = $dbConfig['dbname'] ?? 'bisabantu';
        if ($databaseName === 'bisabantu' && empty($dbConfig['dbname'])) {
            $databaseName = $pdo->query('SELECT DATABASE()')->fetchColumn() ?: 'bisabantu';
        }

        $dump = bisabantu_export_sql_via_pdo($pdo, $databaseName);

        $targetFile = bisabantu_dump_file_path();
        $directory = dirname($targetFile);
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            return false;
        }

        $tempFile = $targetFile . '.tmp';
        if (file_put_contents($tempFile, $dump) === false) {
            return false;
        }

        if (!rename($tempFile, $targetFile)) {
            @unlink($tempFile);
            return false;
        }

        return true;
    } catch (Throwable $e) {
        error_log('[BisaBantu] Gagal sync database dump: ' . $e->getMessage());
        return false;
    }
}

function bisabantu_db_config_from_constants(): array
{
    return [
        'host' => defined('DB_HOST') ? DB_HOST : 'localhost',
        'dbname' => defined('DB_NAME') ? DB_NAME : 'bisabantu',
        'username' => defined('DB_USER') ? DB_USER : 'root',
        'password' => defined('DB_PASS') ? DB_PASS : '',
    ];
}

function bisabantu_sync_sql_dump_after_write(PDO $pdo): void
{
    static $dbConfigLoaded = false;
    static $dbConfig = [];

    if (!$dbConfigLoaded) {
        $dbConfig = bisabantu_db_config_from_constants();
        $dbConfigLoaded = true;
    }

    sync_bisabantu_sql_dump($pdo, $dbConfig);
}
