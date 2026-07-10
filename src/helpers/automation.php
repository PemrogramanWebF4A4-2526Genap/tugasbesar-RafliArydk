<?php
require_once __DIR__ . '/../models/InvoiceModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';

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

    return $invoiceModel->createIfMissing($order_id, $order['order_number'], '');
}

function send_automation_notification($pdo, $userId, $title, $message) {
    $notificationModel = new NotificationModel($pdo);
    return $notificationModel->create((int) $userId, $title, $message);
}

function bisabantu_mail_config(): array {
    $config = [
        'host' => getenv('BISABANTU_SMTP_HOST') ?: '',
        'username' => getenv('BISABANTU_SMTP_USER') ?: '',
        'password' => getenv('BISABANTU_SMTP_PASS') ?: '',
        'port' => (int) (getenv('BISABANTU_SMTP_PORT') ?: 587),
        'from' => getenv('BISABANTU_SMTP_FROM') ?: '',
        'from_name' => getenv('BISABANTU_SMTP_FROM_NAME') ?: 'BisaBantu',
    ];

    $localConfigPath = __DIR__ . '/../config/mail.php';
    if (is_file($localConfigPath)) {
        $localConfig = require $localConfigPath;
        if (is_array($localConfig)) {
            foreach ($localConfig as $key => $value) {
                if (($config[$key] ?? '') === '' || ($key === 'port' && (int) $config[$key] <= 0)) {
                    $config[$key] = $value;
                }
            }
        }
    }

    if ($config['from'] === '') {
        $config['from'] = $config['username'];
    }

    $config['port'] = (int) ($config['port'] ?: 587);

    return $config;
}

function send_automation_email($to, $subject, $message) {
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $smtpConfig = bisabantu_mail_config();
    $smtpHost = $smtpConfig['host'] ?? '';
    $smtpUser = $smtpConfig['username'] ?? '';
    $smtpPass = $smtpConfig['password'] ?? '';
    $smtpPort = (int) ($smtpConfig['port'] ?? 587);
    $smtpFrom = $smtpConfig['from'] ?? $smtpUser;
    $smtpFromName = $smtpConfig['from_name'] ?? 'BisaBantu';

    if ($smtpHost === '' || $smtpUser === '' || $smtpPass === '' || $smtpFrom === '') {
        error_log('Email automation skipped: SMTP environment variables are not configured.');
        return false;
    }

    // Load Composer's autoloader
    require_once __DIR__ . '/../../vendor/autoload.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtpPort;

        // Recipients
        $mail->setFrom($smtpFrom, $smtpFromName);
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
