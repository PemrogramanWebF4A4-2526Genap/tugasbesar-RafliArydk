<?php
class InvoiceModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByOrderId($orderId) {
        $stmt = $this->pdo->prepare('SELECT * FROM invoices WHERE order_id = ? ORDER BY id DESC LIMIT 1');
        $stmt->execute([(int) $orderId]);
        return $stmt->fetch();
    }

    public function createIfMissing($orderId, $orderNumber, $contentPath) {
        $existing = $this->getByOrderId($orderId);
        if ($existing) {
            return $existing;
        }

        $invoiceNumber = 'INV' . date('Ymd') . str_pad((string) $orderId, 4, '0', STR_PAD_LEFT);
        $stmt = $this->pdo->prepare('
            INSERT INTO invoices (order_id, invoice_number, pdf_path, generated_at)
            VALUES (?, ?, ?, NOW())
        ');
        $stmt->execute([(int) $orderId, $invoiceNumber, $contentPath]);

        return [
            'id' => $this->pdo->lastInsertId(),
            'order_id' => (int) $orderId,
            'invoice_number' => $invoiceNumber,
            'pdf_path' => $contentPath,
            'generated_at' => date('Y-m-d H:i:s'),
        ];
    }

    public function getPrintableByOrderId($orderId) {
        $stmt = $this->pdo->prepare('
            SELECT i.*, o.order_number, o.total_price, o.service_date, o.service_address,
                   ub.name AS buyer_name, ub.email AS buyer_email,
                   up.name AS provider_name, up.email AS provider_email
            FROM invoices i
            JOIN orders o ON i.order_id = o.id
            JOIN users ub ON o.buyer_id = ub.id
            JOIN users up ON o.provider_id = up.id
            WHERE i.order_id = ?
            LIMIT 1
        ');
        $stmt->execute([(int) $orderId]);
        return $stmt->fetch();
    }
}
