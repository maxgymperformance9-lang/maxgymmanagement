<?= $this->extend('templates/laporan') ?>

<?= $this->section('content') ?>
<table>
   <tr>
      <td><img src="<?= getLogo(); ?>" width="100px" height="100px"></img></td>
      <td width="100%">
         <h2 align="center">LAPORAN KEUANGAN</h2>
         <h4 align="center"><?= $generalSettings->office_name; ?></h4>
         <h4 align="center">TAHUN BERDIRI <?= $generalSettings->office_year; ?></h4>
      </td>
      <td>
         <div style="width:100px"></div>
      </td>
   </tr>
</table>
<span>Bulan : <?= $bulan; ?></span>
<table align="center" border="1">
   <thead>
      <tr>
         <th align="center">Total Pendapatan</th>
         <th align="center">Jumlah Transaksi</th>
         <th align="center">Rata-rata Transaksi</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td align="right">Rp <?= number_format($summary['total_revenue'] ?? 0, 0, ',', '.'); ?></td>
         <td align="center"><?= $summary['total_transactions'] ?? 0; ?></td>
         <td align="right">Rp <?= number_format($summary['average_transaction'] ?? 0, 0, ',', '.'); ?></td>
      </tr>
   </tbody>
</table>
<br>
<table align="center" border="1">
   <thead>
      <tr>
         <th align="center">Metode Pembayaran</th>
         <th align="center">Jumlah Transaksi</th>
         <th align="center">Total Nominal</th>
      </tr>
   </thead>
   <tbody>
      <?php
      $paymentMethods = ['cash' => 'Tunai', 'card' => 'Kartu', 'transfer' => 'Transfer'];
      foreach ($paymentMethods as $method => $label):
         $data = $summary['payment_methods'][$method] ?? ['count' => 0, 'total_amount' => 0];
      ?>
         <tr>
            <td align="center"><?= $label; ?></td>
            <td align="center"><?= $data['count']; ?></td>
            <td align="right">Rp <?= number_format($data['total_amount'], 0, ',', '.'); ?></td>
         </tr>
      <?php endforeach; ?>
   </tbody>
</table>
<br>
<table align="center" border="1">
   <thead>
      <tr>
         <th align="center">Total Pengeluaran</th>
         <th align="center">Jumlah Item Pengeluaran</th>
         <th align="center">Rata-rata Pengeluaran</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td align="right">Rp <?= number_format($expenseSummary['total_expense_amount'] ?? 0, 0, ',', '.'); ?></td>
         <td align="center"><?= $expenseSummary['total_expenses'] ?? 0; ?></td>
         <td align="right">Rp <?= number_format($expenseSummary['average_expense'] ?? 0, 0, ',', '.'); ?></td>
      </tr>
   </tbody>
</table>
<br>
<table align="center" border="1">
   <thead>
      <tr>
         <th align="center">Laba Bersih (Pendapatan - Pengeluaran)</th>
      </tr>
   </thead>
   <tbody>
      <tr>
         <td align="right">Rp <?= number_format($netProfit ?? 0, 0, ',', '.'); ?></td>
      </tr>
   </tbody>
</table>
<?= $this->endSection() ?>
