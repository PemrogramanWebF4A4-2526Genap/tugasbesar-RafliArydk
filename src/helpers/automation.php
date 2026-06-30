<?php
require_once __DIR__ . '/../models/InvoiceModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';

function calculate_platform_fee($amount, $commissionRate = 10) {
    return round(((float) $amount * (float) $commissionRate) / 100);
}

function generate_invoice($pdo, $order_id) {
    $invoiceModel = new InvoiceModel($pdo);
    $existing = $invoiceModel->getByOrderId($order_id);
    if ($existing) {
        return $existing;
    }

    $stmt = $pdo->prepare('
        SELECT o.*, ub.name AS buyer_name, ub.email AS buyer_email, up.name AS provider_name
        FROM orders o
        JOIN users ub ON o.buyer_id = ub.id
        JOIN users up ON o.provider_id = up.id
        WHERE o.id = ?
    ');
    $stmt->execute([(int) $order_id]);
    $order = $stmt->fetch();
    if (!$order) {
        return null;
    }

    $safeNumber = preg_replace('/[^A-Za-z0-9_-]/', '', $order['order_number']);
    $relativePath = 'src/assets/invoices/invoice_' . $safeNumber . '.html';
    $absolutePath = __DIR__ . '/../' . $relativePath;
    $dir = dirname($absolutePath);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $invoice = $invoiceModel->createIfMissing($order_id, $order['order_number'], $relativePath);
    $html = '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>' . htmlspecialchars($invoice['invoice_number'], ENT_QUOTES, 'UTF-8') . '</title>'
        . '<style>body{font-family:Arial,sans-serif;margin:40px;color:#1f2937}.box{border:1px solid #ddd;padding:24px;max-width:720px}.muted{color:#6b7280}.total{font-size:22px;font-weight:700}</style></head><body>'
        . '<div class="box"><h1>Invoice BisaBantu</h1><p class="muted">' . htmlspecialchars($invoice['invoice_number'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p><strong>No. Pesanan:</strong> ' . htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p><strong>Pembeli:</strong> ' . htmlspecialchars($order['buyer_name'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p><strong>Penyedia:</strong> ' . htmlspecialchars($order['provider_name'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p><strong>Tanggal Layanan:</strong> ' . htmlspecialchars($order['service_date'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p><strong>Alamat:</strong> ' . nl2br(htmlspecialchars($order['service_address'], ENT_QUOTES, 'UTF-8')) . '</p>'
        . '<hr><p class="total">Total: Rp' . number_format((float) $order['total_price'], 0, ',', '.') . '</p>'
        . '<p class="muted">Invoice otomatis dibuat saat pembayaran diverifikasi.</p></div></body></html>';
    file_put_contents($absolutePath, $html);

    return $invoice;
}

function send_automation_notification($pdo, $userId, $title, $message) {
    $notificationModel = new NotificationModel($pdo);
    return $notificationModel->create((int) $userId, $title, $message);
}

function send_automation_email($to, $subject, $message) {
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Load Composer's autoloader
    require_once __DIR__ . '/../vendor/autoload.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rafliaryadika100@gmail.com';
        $mail->Password   = 'flsfkfnjjowegmbe';
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('rafliaryadika100@gmail.com', 'BisaBantu');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (\Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

function after_payment_verified($pdo, array $payment) {
    $invoice = generate_invoice($pdo, $payment['order_id']);

    send_automation_notification($pdo, $payment['buyer_id'], 'Pembayaran Berhasil', "Pembayaran untuk pesanan {$payment['order_number']} telah diverifikasi.");
    send_automation_notification($pdo, $payment['provider_id'], 'Pesanan Dibayar', "Pesanan {$payment['order_number']} telah dibayar oleh pembeli. Silakan kerjakan.");

    if (!empty($payment['buyer_email'])) {
        send_automation_email($payment['buyer_email'], 'Invoice BisaBantu ' . ($invoice['invoice_number'] ?? ''), "Pembayaran pesanan {$payment['order_number']} berhasil diverifikasi.");
    }

    return $invoice;
}
