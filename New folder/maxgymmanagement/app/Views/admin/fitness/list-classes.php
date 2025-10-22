<?php if (empty($classes)): ?>
   <div class="card-body">
      <div class="row">
         <div class="col-sm-12 text-center">
            <h5 class="info-title">Tidak ada data</h5>
            <p class="card-category">Belum ada data kelas fitness</p>
         </div>
      </div>
   </div>
<?php else: ?>
   <div class="card-body">
      <div class="table-responsive">
         <table class="table table-hover">
            <thead class="text-primary">
               <th>No</th>
               <th>Nama Kelas</th>
               <th>Durasi</th>
               <th>Kapasitas</th>
               <th>Harga</th>
               <th>Status</th>
               <th>Aksi</th>
            </thead>
            <tbody>
               <?php $no = 1; foreach ($classes as $class): ?>
                  <tr>
                     <td><?= $no++ ?></td>
                     <td><?= esc($class['nama_class']) ?></td>
                     <td><?= $class['durasi'] ?> menit</td>
                     <td><?= $class['kapasitas'] ?> orang</td>
                     <td>Rp <?= number_format($class['harga'], 0, ',', '.') ?></td>
                     <td>
                        <span class="badge badge-<?= $class['status'] == 'aktif' ? 'success' : 'danger' ?>">
                           <?= ucfirst($class['status']) ?>
                        </span>
                     </td>
                     <td>
                        <a href="<?= base_url('admin/fitness-classes/edit/' . $class['id_class']) ?>" class="btn btn-sm btn-primary">
                           <i class="material-icons">edit</i>
                        </a>
                        <button onclick="toggleStatus(<?= $class['id_class'] ?>)" class="btn btn-sm btn-warning">
                           <i class="material-icons">power_settings_new</i>
                        </button>
                        <button onclick="deleteClass(<?= $class['id_class'] ?>)" class="btn btn-sm btn-danger">
                           <i class="material-icons">delete</i>
                        </button>
                     </td>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
<?php endif; ?>

<script>
   function deleteClass(id) {
      if (confirm('Yakin ingin menghapus kelas fitness ini?')) {
         jQuery.ajax({
            url: "<?= base_url('admin/fitness-classes/delete/') ?>" + id,
            type: 'DELETE',
            success: function(response) {
               getDataClasses();
            },
            error: function() {
               alert('Gagal menghapus data');
            }
         });
      }
   }

   function toggleStatus(id) {
      if (confirm('Yakin ingin mengubah status kelas fitness ini?')) {
         jQuery.ajax({
            url: "<?= base_url('admin/fitness-classes/toggle-status/') ?>" + id,
            type: 'POST',
            success: function(response) {
               getDataClasses();
            },
            error: function() {
               alert('Gagal mengubah status');
            }
         });
      }
   }
</script>
