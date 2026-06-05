<?php
require_once __DIR__ . '/../models/OrderModel.php';

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: index.php?page=home');
    exit;
}

$type = $_GET['type'] ?? 'orders';
$orderModel = new OrderModel($pdo);

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="bisabantu_' . preg_replace('/[^a-z0-9_-]/i', '', $type) . '_' . date('Ymd_His') . '.csv"');

$out = fopen('php://output', 'w');
fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

if ($type === 'top_services') {
    fputcsv($out, ['Rank', 'Jasa', 'Jumlah Pesanan', 'Pendapatan']);
    foreach ($orderModel->getTopServices(50) as $index => $service) {
        fputcsv($out, [
            $index + 1,
            $service['title'],
            (int) $service['order_count'],
            (float) $service['revenue'],
        ]);
    }
    fclose($out);
    exit;
}

fputcsv($out, ['No Pesanan', 'Pembeli', 'Penyedia', 'Jasa', 'Status', 'Total', 'Tanggal']);
foreach ($orderModel->getAllWithServices() as $order) {
    fputcsv($out, [
        $order['order_number'],
        $order['buyer_name'],
        $order['provider_name'],
        $order['service_title'] ?? '-',
        $order['status'],
        (float) $order['total_price'],
        $order['created_at'],
    ]);
}

fclose($out);
exit;
