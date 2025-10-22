mut<table style="width: 100%; margin-bottom: 20px;">
   <tr>
      <td style="width: 100px;">
         <img src="<?= getLogo(); ?>" width="80px" height="80px" style="display: block;">
      </td>
      <td style="text-align: center; vertical-align: middle;">
         <h2 style="margin: 0; font-size: 16px;">LAPORAN KEUANGAN</h2>
         <h4 style="margin: 5px 0; font-size: 14px;"><?= $generalSettings->office_name; ?></h4>
         <h4 style="margin: 5px 0; font-size: 14px;">TAHUN BERDIRI <?= $generalSettings->office_year; ?></h4>
      </td>
      <td style="width: 100px;"></td>
   </tr>
</table>

<p style="margin-bottom: 10px;"><strong>Bulan: <?= $bulan; ?></strong></p>

<table style="width: 80%; border-collapse: collapse; margin: 0 auto 15px auto;">
   <thead>
      <tr>
         <th style="border: 1px solid #000; padding: 8px; text-align: center; background-color: #f5f5f5;">Total Pendapatan</th>
         <th style="border: 1px solid #000; padding: 8px; text-align: center; background-color: #f5f5f5;">Jumlah Transaksi</th>
         <th style="border: 1px solid #000; padding: 8px; text-align: center; background-color: #f5f5f5;">Rata-rata Transaksi</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td style="border: 1px solid #000; padding: 8px; text-align: right;">Rp <?= number_format($summary['total_revenue'] ?? 0, 0, ',', '.'); ?></td>
         <td style="border: 1px solid #000; padding: 8px; text-align: center;"><?= $summary['total_transactions'] ?? 0; ?></td>
         <td style="border: 1px solid #000; padding: 8px; text-align: right;">Rp <?= number_format($summary['average_transaction'] ?? 0, 0, ',', '.'); ?></td>
      </tr>
   </tbody>
</table>

<table style="width: 80%; border-collapse: collapse; margin: 15px auto;">
   <thead>
      <tr>
         <th style="border: 1px solid #000; padding: 8px; text-align: center; background-color: #f5f5f5;">Metode Pembayaran</th>
         <th style="border: 1px solid #000; padding: 8px; text-align: center; background-color: #f5f5f5;">Jumlah Transaksi</th>
         <th style="border: 1px solid #000; padding: 8px; text-align: center; background-color: #f5f5f5;">Total Nominal</th>
      </tr>
   </thead>
   <tbody>
      <?php
      $paymentMethods = ['cash' => 'Tunai', 'card' => 'Kartu', 'transfer' => 'Transfer'];
      foreach ($paymentMethods as $method => $label):
         $data = $summary['payment_methods'][$method] ?? ['count' => 0, 'total_amount' => 0];
      ?>
         <tr>
            <td style="border: 1px solid #000; padding: 8px; text-align: center;"><?= $label; ?></td>
            <td style="border: 1px solid #000; padding: 8px; text-align: center;"><?= $data['count']; ?></td>
            <td style="border: 1px solid #000; padding: 8px; text-align: right;">Rp <?= number_format($data['total_amount'], 0, ',', '.'); ?></td>
         </tr>
      <?php endforeach; ?>
   </tbody>
</table>

<table style="width: 80%; border-collapse: collapse; margin: 15px auto;">
   <thead>
      <tr>
         <th style="border: 1px solid #000; padding: 8px; text-align: center; background-color: #f5f5f5;">Total Pengeluaran</th>
         <th style="border: 1px solid #000; padding: 8px; text-align: center; background-color: #f5f5f5;">Jumlah Item Pengeluaran</th>
         <th style="border: 1px solid #000; padding: 8px; text-align: center; background-color: #f5f5f5;">Rata-rata Pengeluaran</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td style="border: 1px solid #000; padding: 8px; text-align: right;">Rp <?= number_format($expenseSummary['total_expense_amount'] ?? 0, 0, ',', '.'); ?></td>
         <td style="border: 1px solid #000; padding: 8px; text-align: center;"><?= $expenseSummary['total_expenses'] ?? 0; ?></td>
         <td style="border: 1px solid #000; padding: 8px; text-align: right;">Rp <?= number_format($expenseSummary['average_expense'] ?? 0, 0, ',', '.'); ?></td>
      </tr>
   </tbody>
</table>

<table style="width: 60%; border-collapse: collapse; margin: 15px auto;">
   <thead>
      <tr>
         <th style="border: 1px solid #000; padding: 8px; text-align: center; background-color: #f5f5f5;">Laba Bersih (Pendapatan - Pengeluaran)</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td style="border: 1px solid #000; padding: 8px; text-align: right; font-weight: bold;">Rp <?= number_format($netProfit ?? 0, 0, ',', '.'); ?></td>
      </tr>
   </tbody>
</table>
