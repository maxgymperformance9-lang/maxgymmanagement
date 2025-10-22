<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header card-header-primary">
                  <h4 class="card-title">Jadwal Kelas Fitness</h4>
                  <p class="card-category">Daftar jadwal kelas fitness MAXGYM</p>
               </div>
               <div class="card-body">
                  <?php if (empty($schedules)): ?>
                     <div class="text-center">
                        <h5 class="info-title">Tidak ada jadwal kelas</h5>
                        <p class="card-category">Belum ada jadwal kelas fitness</p>
                     </div>
                  <?php else: ?>
                     <div class="table-responsive">
                        <table class="table table-hover">
                           <thead class="text-primary">
                              <th>No</th>
                              <th>Nama Kelas</th>
                              <th>Tanggal</th>
                              <th>Waktu Mulai</th>
                              <th>Waktu Selesai</th>
                              <th>Instruktur</th>
                              <th>Lokasi</th>
                              <th>Status</th>
                           </thead>
                           <tbody>
                              <?php $no = 1; foreach ($schedules as $schedule): ?>
                                 <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($schedule['nama_class']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($schedule['tanggal'])) ?></td>
                                    <td><?= $schedule['waktu_mulai'] ?></td>
                                    <td><?= $schedule['waktu_selesai'] ?></td>
                                    <td><?= esc($schedule['instructor'] ?? '-') ?></td>
                                    <td><?= esc($schedule['lokasi'] ?? '-') ?></td>
                                    <td>
                                       <span class="badge badge-<?= $schedule['status'] == 'scheduled' ? 'info' : ($schedule['status'] == 'ongoing' ? 'success' : ($schedule['status'] == 'completed' ? 'primary' : 'danger')) ?>">
                                          <?= ucfirst($schedule['status']) ?>
                                       </span>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                           </tbody>
                        </table>
                     </div>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
