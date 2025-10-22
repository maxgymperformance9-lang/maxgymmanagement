<div class="table-responsive">
   <table class="table table-striped">
      <thead>
         <tr>
            <th>No</th>
            <th>ID Pengeluaran</th>
            <th>Deskripsi</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Tanggal</th>
            <th>Aksi</th>
         </tr>
      </thead>
      <tbody>
         <?php $i = 1; ?>
         <?php if (empty($pengeluaran)): ?>
            <tr>
               <td colspan="7" class="text-center">Tidak ada data pengeluaran untuk bulan ini</td>
            </tr>
         <?php else: ?>
            <?php foreach ($pengeluaran as $expense): ?>
               <tr>
                  <td><?= $i++; ?></td>
                  <td><?= $expense['id_expense']; ?></td>
                  <td><?= $expense['description']; ?></td>
                  <td><?= $expense['category']; ?></td>
                  <td>Rp <?= number_format($expense['amount'], 0, ',', '.'); ?></td>
                  <td><?= date('d-m-Y', strtotime($expense['expense_date'])); ?></td>
                  <td>
                     <a href="<?= base_url('admin/pengeluaran/edit/' . $expense['id_expense']); ?>" class="btn btn-warning btn-sm">
                        <i class="material-icons">edit</i>
                     </a>
                     <button onclick="hapusPengeluaran('<?= $expense['id_expense']; ?>')" class="btn btn-danger btn-sm">
                        <i class="material-icons">delete</i>
                     </button>
                  </td>
               </tr>
            <?php endforeach; ?>
         <?php endif; ?>
      </tbody>
   </table>
</div>

<script>
   function hapusPengeluaran(idExpense) {
      if (confirm('Apakah Anda yakin ingin menghapus pengeluaran ini?')) {
         jQuery.ajax({
            url: "<?= base_url('/admin/pengeluaran/delete/'); ?>" + idExpense,
            type: 'post',
            success: function(response, status, xhr) {
               if (response['status']) {
                  alert(response['message']);
                  ambilDataPengeluaran();
               } else {
                  alert(response['message']);
               }
            },
            error: function(xhr, status, thrown) {
               console.log(thrown);
               alert('Gagal menghapus pengeluaran\n' + thrown);
            }
         });
      }
   }
</script>
