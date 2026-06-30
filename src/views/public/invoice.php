<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($invoice['invoice_number']) ?> - BisaBantu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f6f3ed; color: #231f18; }
        .invoice-sheet { max-width: 820px; margin: 32px auto; background: #fff; padding: 36px; border: 1px solid #e7dfd1; }
        .invoice-brand { color: #8a5a12; font-weight: 800; letter-spacing: 0; }
        @media print { body { background: #fff; } .no-print { display: none; } .invoice-sheet { border: 0; margin: 0; max-width: none; } }
    </style>
</head>
<body>
    <main class="invoice-sheet">
        <div class="d-flex justify-content-between gap-3 flex-wrap">
            <div>
                <h1 class="invoice-brand mb-1">BisaBantu</h1>
                <p class="text-muted mb-0">Invoice marketplace jasa lokal</p>
            </div>
            <div class="text-end">
                <h2 class="h5 mb-1"><?= e($invoice['invoice_number']) ?></h2>
                <p class="text-muted mb-0"><?= e(date('d M Y H:i', strtotime($invoice['generated_at']))) ?></p>
            </div>
        </div>
        <hr class="my-4">
        <div class="row g-4">
            <div class="col-md-6">
                <h3 class="h6">Pembeli</h3>
                <p class="mb-0"><?= e($invoice['buyer_name']) ?></p>
                <p class="text-muted"><?= e($invoice['buyer_email']) ?></p>
            </div>
            <div class="col-md-6">
                <h3 class="h6">Penyedia</h3>
                <p class="mb-0"><?= e($invoice['provider_name']) ?></p>
                <p class="text-muted"><?= e($invoice['provider_email']) ?></p>
            </div>
        </div>
        <div class="mt-3">
            <p class="mb-1"><strong>No. Pesanan:</strong> <?= e($invoice['order_number']) ?></p>
            <p class="mb-1"><strong>Tanggal Layanan:</strong> <?= e(date('d M Y', strtotime($invoice['service_date']))) ?></p>
            <p><strong>Alamat:</strong> <?= nl2br(e($invoice['service_address'])) ?></p>
        </div>
        <div class="table-responsive mt-4">
            <table class="table">
                <thead><tr><th>Jasa</th><th>Qty</th><th class="text-end">Subtotal</th></tr></thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= e($item['title']) ?></td>
                            <td><?= (int) $item['quantity'] ?></td>
                            <td class="text-end"><?= e(format_rupiah($item['quantity'] * $item['price_per_unit'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr><th colspan="2" class="text-end">Total</th><th class="text-end"><?= e(format_rupiah($invoice['total_price'])) ?></th></tr>
                </tfoot>
            </table>
        </div>
        <div class="d-flex gap-2 no-print mt-4">
            <button type="button" class="btn btn-dark" onclick="window.print()">Cetak</button>
            <a href="<?= base_url('index.php?page=orders') ?>" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </main>
</body>
</html>
