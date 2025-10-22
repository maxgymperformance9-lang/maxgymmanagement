<?= $this->extend('templates/laporan') ?>

<?= $this->section('content') ?>
<table>
   <tr>
      <td><img src="<?= getLogo(); ?>" width="100px" height="100px"></img></td>
      <td width="100%">
         <h2 align="center">LAPORAN TRANSAKSI</h2>
         <h4 align="center"><?= $generalSettings->office_name; ?></h4>
         <h4 align="center">TAHUN BERDIRI <?= $generalSettings->office_year; ?></h4>
      </td>
      <td>
         <div style="width:100px"></div>
      </td>
   </tr>
</table>
<span>Periode : <?= $startDate; ?> sampai <?= $endDate; ?></span>
<table align="center" border="1">
   <thead>
      <tr>
         <th align="center">No</th>
         <th align="center">ID Transaksi</th>
         <th align="center">Tanggal</th>
         <th align="center">Member</th>
         <th align="center">Items</th>
         <th align="center">Total</th>
         <th align="center">Status</th>
         <th align="center">Metode Pembayaran</th>
      </tr>
   </thead>
   <tbody>
      <?php $i = 1; ?>
      <?php foreach ($transactions as $transaction) : ?>
         <tr>
            <td align="center"><?= $i++; ?></td>
            <td align="center"><?= $transaction['id_transaction']; ?></td>
            <td align="center"><?= date('d-m-Y', strtotime($transaction['tanggal'])); ?></td>
            <td><?= $transaction['nama_member'] ?? 'Non-Member'; ?></td>
            <td>
               <?php foreach ($transaction['items'] as $item): ?>
                  <?= esc($item['nama_produk']) ?> (<?= $item['quantity'] ?>x)<br>
               <?php endforeach; ?>
            </td>
            <td align="right">Rp <?= number_format($transaction['total'], 0, ',', '.'); ?></td>
            <td align="center"><?= ucfirst($transaction['status']); ?></td>
            <td align="center"><?= ucfirst($transaction['payment_method']); ?></td>
         </tr>
      <?php endforeach; ?>
   </tbody>
</table>
<br></br>
<table>
   <tr>
      <td>Total Transaksi</td>
      <td>: <?= count($transactions); ?></td>
   </tr>
</table>
<?= $this->endSection() ?>
