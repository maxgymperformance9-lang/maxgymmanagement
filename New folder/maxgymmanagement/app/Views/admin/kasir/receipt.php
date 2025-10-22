<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .line {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 2px 0;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="center">
        <h2>MAXGYM PERFORMANCE</h2>
        <p>Struk Transaksi</p>
        <p>Jl.Hj kokon Komariah Rt 003 Rw 012</p>
        <p>Telp: +62 815-6344-7530</p>
    </div>
    <div class="line"></div>
    <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($transaction['tanggal'])) ?></p>
    <p><strong>ID Transaksi:</strong> <?= $transaction['id_transaction'] ?></p>
    <?php if ($member): ?>
        <p><strong>Member:</strong> <?= esc($member['nama_member']) ?> (<?= $member['no_member'] ?>)</p>
    <?php endif; ?>
    <div class="line"></div>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Qty</th>
                <th class="right">Harga</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <?= esc($item['nama_produk']) ?>
                        <?php if (isset($item['id_package']) && !empty($item['id_package'])): ?>
                            <br><small>(Paket Membership)</small>
                        <?php endif; ?>
                    </td>
                    <td class="right"><?= $item['quantity'] ?></td>
                    <td class="right"><?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td class="right"><?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?> IDR</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="line"></div>
    <p><strong>Subtotal:</strong> <span class="right">Rp <?= number_format($transaction['total'], 0, ',', '.') ?></span></p>
    <?php if ($transaction['ppn_percentage'] > 0): ?>
        <p><strong>PPN (<?= $transaction['ppn_percentage'] ?>%):</strong> <span class="right">Rp <?= number_format($transaction['ppn_amount'], 0, ',', '.') ?></span></p>
    <?php endif; ?>
    <?php if ($transaction['discount_percentage'] > 0): ?>
        <p><strong>Diskon (<?= $transaction['discount_percentage'] ?>%):</strong> <span class="right">Rp <?= number_format($transaction['discount_amount'], 0, ',', '.') ?></span></p>
    <?php endif; ?>
    <p class="total"><strong>Grand Total:</strong> <span class="right">Rp <?= number_format($transaction['grand_total'], 0, ',', '.') ?></span></p>
    <p><strong>Jumlah Dibayar:</strong> <span class="right">Rp <?= number_format($transaction['payment_amount'], 0, ',', '.') ?></span></p>
    <p><strong>Kembalian:</strong> <span class="right">Rp <?= number_format($transaction['change_amount'], 0, ',', '.') ?></span></p>
    <p><strong>Metode Pembayaran:</strong> <?= ucfirst($transaction['payment_method']) ?></p>
    <div class="line"></div>
    <div class="center">
        <p>Terima Kasih Atas Kunjungannya</p>
        <p>Hormat Kami Maxgym Performance</p>
    </div>
</body>
</html>
