<?php
function generate_invoice($pdo, $order_id) {
    // Generate invoice logic
    $invoice_number = 'INV/' . date('Ymd') . '/' . str_pad($order_id, 4, '0', STR_PAD_LEFT);
    $pdf_path = 'assets/invoices/' . $invoice_number . '.pdf'; // Just a mock path for now, actual PDF generation requires FPDF or dompdf

    $stmt = $pdo->prepare('INSERT INTO invoices (order_id, invoice_number, pdf_path, generated_at) VALUES (?, ?, ?, NOW())');
    try {
        $stmt->execute([$order_id, $invoice_number, $pdf_path]);
    } catch (PDOException $e) {
        // Invoice might already exist
    }
}
