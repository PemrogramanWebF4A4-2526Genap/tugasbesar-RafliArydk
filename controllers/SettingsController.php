<?php
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: index.php?page=home');
    exit;
}

header('Location: index.php?page=admin_settings');
exit;
