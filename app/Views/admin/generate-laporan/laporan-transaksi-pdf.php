<table style="width: 100%; margin-bottom: 20px;">
   <tr>
      <td style="width: 100px;">
         <img src="<?= getLogo(); ?>" width="80px" height="80px" style="display: block;">
      </td>
      <td style="text-align: center; vertical-align: middle;">
         <h2 style="margin: 0; font-size: 16px;">LAPORAN TRANSAKSI</h2>
         <h4 style="margin: 5px 0; font-size: 14px;"><?= $generalSettings->office_name; ?></h4>
         <h4 style="margin: 5px 0; font-size: 14px;">TAHUN BERDIRI <?= $generalSettings->office_year; ?></h4>
      </td>
      <td style="width: 100px;"></td>
   </tr>
</table>

<p style="margin-bottom: 10px;"><strong>Periode: <?= $startDate; ?> sampai <?= $endDate; ?></strong></p>

<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
   <thead>
      <tr>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 5%;">No</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 12%;">ID Transaksi</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 10%;">Tanggal</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: left; width: 18%;">Member</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: left; width: 25%;">Items</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: right; width: 12%;">Total</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 9%;">Status</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 9%;">Metode</th>
      </tr>
   </thead>
   <tbody>
      <?php $i = 1; ?>
      <?php foreach ($transactions as $transaction) : ?>
         <tr>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;"><?= $i++; ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;"><?= $transaction['id_transaction']; ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;"><?= date('d-m-Y', strtotime($transaction['tanggal'])); ?></td>
            <td style="border: 1px solid #000; padding: 5px;"><?= $transaction['nama_member'] ?? 'Non-Member'; ?></td>
            <td style="border: 1px solid #000; padding: 5px;">
               <?php foreach ($transaction['items'] as $item): ?>
                  <?= esc($item['nama_produk']) ?> (<?= $item['quantity'] ?>x)<br>
               <?php endforeach; ?>
            </td>
            <td style="border: 1px solid #000; padding: 5px; text-align: right;">Rp <?= number_format($transaction['total'], 0, ',', '.'); ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;"><?= ucfirst($transaction['status']); ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;"><?= ucfirst($transaction['payment_method']); ?></td>
         </tr>
      <?php endforeach; ?>
   </tbody>
</table>

<table style="width: 30%; margin-bottom: 20px;">
   <tr>
      <td style="padding: 5px; font-weight: bold; width: 50%;">Total Transaksi</td>
      <td style="padding: 5px;">: <?= count($transactions); ?></td>
   </tr>
</table>
