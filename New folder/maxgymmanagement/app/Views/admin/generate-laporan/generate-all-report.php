<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header card-header-primary">
                  <h4 class="card-title">Laporan Keseluruhan</h4>
                  <p class="card-category">Periode: <?= $periode ?? 'Tidak ditentukan' ?></p>
               </div>
               <div class="card-body">
                  <?php if (!empty($laporanAbsenPegawai)): ?>
                     <h5>Laporan Absensi Pegawai</h5>
                     <?= view('admin/generate-laporan/laporan-pegawai', $laporanAbsenPegawai) ?>
                     <hr>
                  <?php endif; ?>
                  <?php if (!empty($laporanAbsenMember)): ?>
                     <h5>Laporan Absensi Member</h5>
                     <?= view('admin/generate-laporan/laporan-absensi-member', $laporanAbsenMember) ?>
                     <hr>
                  <?php endif; ?>
                  <?php if (!empty($laporanDataMember)): ?>
                     <h5>Laporan Data Member</h5>
                     <?= view('admin/generate-laporan/laporan-data-member', $laporanDataMember) ?>
                     <hr>
                  <?php endif; ?>
                  <?php if (!empty($laporanTransaksi)): ?>
                     <h5>Laporan Transaksi</h5>
                     <?= view('admin/generate-laporan/laporan-transaksi', $laporanTransaksi) ?>
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
                        <?= view('admin/generate-laporan/laporan-keuangan', $laporanKeuangan) ?>
                     <?php endif; ?>
                     <hr>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
