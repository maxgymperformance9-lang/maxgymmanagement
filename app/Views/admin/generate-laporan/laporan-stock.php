<?= $this->extend('templates/laporan') ?>

<?= $this->section('content') ?>
<table>
   <tr>
      <td><img src="<?= getLogo(); ?>" width="100px" height="100px"></img></td>
      <td width="100%">
         <h2 align="center">LAPORAN PERGERAKAN STOK</h2>
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
         <th align="center">Tanggal</th>
         <th align="center">Produk</th>
         <th align="center">Tipe Pergerakan</th>
         <th align="center">Quantity</th>
         <th align="center">Gudang</th>
         <th align="center">Gudang Asal</th>
         <th align="center">Gudang Tujuan</th>
         <th align="center">Referensi</th>
         <th align="center">Catatan</th>
      </tr>
   </thead>
   <tbody>
      <?php $i = 1; ?>
      <?php foreach ($stockMovements as $movement) : ?>
         <tr>
            <td align="center"><?= $i++; ?></td>
            <td align="center"><?= date('d-m-Y H:i', strtotime($movement['created_at'])); ?></td>
            <td><?= esc($movement['nama_produk']); ?></td>
            <td align="center"><?= ucfirst($movement['movement_type']); ?></td>
            <td align="center"><?= $movement['quantity']; ?></td>
            <td align="center"><?= esc($movement['nama_gudang']); ?></td>
            <td align="center"><?= esc($movement['from_warehouse_name'] ?? '-'); ?></td>
            <td align="center"><?= esc($movement['to_warehouse_name'] ?? '-'); ?></td>
            <td align="center"><?= esc($movement['reference'] ?? '-'); ?></td>
            <td align="center"><?= esc($movement['notes'] ?? '-'); ?></td>
         </tr>
      <?php endforeach; ?>
   </tbody>
</table>
<br></br>
<table>
   <tr>
      <td>Total Pergerakan Stok</td>
      <td>: <?= count($stockMovements); ?></td>
   </tr>
</table>
<?= $this->endSection() ?>
