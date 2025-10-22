<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Laporan Keseluruhan</title>
   <style>
      body {
         font-family: Arial, Helvetica, sans-serif;
         font-size: 12px;
         line-height: 1.4;
      }

      .content {
         padding: 20px;
      }

      .card {
         border: 1px solid #ddd;
         border-radius: 5px;
         margin-bottom: 20px;
      }

      .card-header {
         background-color: #f5f5f5;
         padding: 10px;
         border-bottom: 1px solid #ddd;
      }

      .card-title {
         margin: 0;
         font-size: 18px;
         font-weight: bold;
      }

      .card-category {
         margin: 5px 0 0 0;
         color: #666;
      }

      .card-body {
         padding: 15px;
      }

      h5 {
         margin-top: 20px;
         margin-bottom: 10px;
         font-size: 14px;
         font-weight: bold;
         border-bottom: 1px solid #ddd;
         padding-bottom: 5px;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 15px;
      }

      table, th, td {
         border: 1px solid #ddd;
      }

      th, td {
         padding: 8px;
         text-align: left;
      }

      th {
         background-color: #f5f5f5;
         font-weight: bold;
      }

      .table-striped tbody tr:nth-child(odd) {
         background-color: #f9f9f9;
      }

      hr {
         border: none;
         border-top: 1px solid #ddd;
         margin: 20px 0;
      }

      .text-right {
         text-align: right;
      }

      .text-center {
         text-align: center;
      }

      .bg-lightgreen {
         background-color: #d4edda !important;
      }

      .bg-yellow {
         background-color: #fff3cd !important;
      }

      .bg-red {
         background-color: #f8d7da !important;
      }
   </style>
</head>

<body>
   <div class="content">
      <div class="card">
         <div class="card-header">
            <h4 class="card-title">Laporan Keseluruhan</h4>
            <p class="card-category">Periode: <?= $periode ?? 'Tidak ditentukan' ?></p>
         </div>
         <div class="card-body">
            <?php if (!empty($laporanAbsenPegawai)): ?>
               <h5>Laporan Absensi Pegawai</h5>
               <?= view('admin/generate-laporan/laporan-pegawai-pdf', $laporanAbsenPegawai) ?>
               <hr>
            <?php endif; ?>
            <?php if (!empty($laporanAbsenMember)): ?>
               <h5>Laporan Absensi Member</h5>
               <?= view('admin/generate-laporan/laporan-absensi-member-pdf', $laporanAbsenMember) ?>
               <hr>
            <?php endif; ?>
            <?php if (!empty($laporanDataMember)): ?>
               <h5>Laporan Data Member</h5>
               <?= view('admin/generate-laporan/laporan-data-member-pdf', $laporanDataMember) ?>
               <hr>
            <?php endif; ?>
            <?php if (!empty($laporanTransaksi)): ?>
               <h5>Laporan Transaksi</h5>
               <?= view('admin/generate-laporan/laporan-transaksi-pdf', $laporanTransaksi) ?>
               <hr>
            <?php endif; ?>
            <?php if (!empty($laporanKeuangan)): ?>
               <h5>Laporan Keuangan</h5>
               <?php if (isset($laporanKeuangan['summaryTahunan'])): ?>
                  <!-- Tahunan view -->
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th>Bulan</th>
                           <th>Pendapatan</th>
                           <th>Pengeluaran</th>
                           <th>Laba Bersih</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($laporanKeuangan['summaryTahunan'] as $summary): ?>
                           <tr>
                              <td><?= date('F Y', strtotime($summary['bulan'])) ?></td>
                              <td>Rp <?= number_format($summary['revenue'], 0, ',', '.') ?></td>
                              <td>Rp <?= number_format($summary['expenses'], 0, ',', '.') ?></td>
                              <td>Rp <?= number_format($summary['net_profit'], 0, ',', '.') ?></td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                     <tfoot>
                        <tr>
                           <th>Total</th>
                           <th>Rp <?= number_format(array_sum(array_column($laporanKeuangan['summaryTahunan'], 'revenue')), 0, ',', '.') ?></th>
                           <th>Rp <?= number_format(array_sum(array_column($laporanKeuangan['summaryTahunan'], 'expenses')), 0, ',', '.') ?></th>
                           <th>Rp <?= number_format($laporanKeuangan['netProfitTahunan'], 0, ',', '.') ?></th>
                        </tr>
                     </tfoot>
                  </table>
               <?php else: ?>
                  <?= view('admin/generate-laporan/laporan-keuangan-pdf', $laporanKeuangan) ?>
               <?php endif; ?>
               <hr>
            <?php endif; ?>
         </div>
      </div>
   </div>
</body>

</html>
