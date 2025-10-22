<?php if ($empty): ?>
   <div class="card-body">
      <div class="row">
         <div class="col-sm-12 text-center">
            <h5 class="info-title">Tidak ada data</h5>
            <p class="card-category">Belum ada data transaksi</p>
         </div>
      </div>
   </div>
<?php else: ?>
   <div class="card-body">
      <div class="table-responsive">
         <table class="table table-hover">
            <thead class="text-primary">
               <th>No</th>
               <th>ID Transaksi</th>
               <th>Jumlah Item</th>
               <th>Tanggal</th>
               <th>Member</th>
               <th>Metode Pembayaran</th>
               <th>Total</th>
               <th>Aksi</th>
            </thead>
            <tbody>
               <?php $no = 1; foreach ($data as $row): ?>
                  <tr>
                     <td><?= $no++ ?></td>
                     <td><?= esc($row['id_transaction']) ?></td>
                     <td><?php
                        $transactionItemModel = new \App\Models\TransactionItemModel();
                        $items = $transactionItemModel->where('id_transaction', $row['id_transaction'])->findAll();
                        echo count($items) . ' item';
                     ?></td>
                     <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                     <td><?= esc($row['nama_member'] ?? 'Non-Member') ?></td>
                     <td><?= esc(ucfirst($row['payment_method'])) ?></td>
                     <td><?= number_format($row['grand_total'], 0, ',', '.') ?> IDR</td>
                     <td>
                        <a href="<?= base_url('admin/kasir/transaksi/view/' . $row['id_transaction']) ?>" class="btn btn-sm btn-info">
                           <i class="material-icons">visibility</i>
                        </a>
                        <a href="<?= base_url('admin/kasir/receipt/' . $row['id_transaction']) ?>" target="_blank" class="btn btn-sm btn-success">
                           <i class="material-icons">print</i>
                        </a>
                        <button onclick="deleteTransaksi('<?= $row['id_transaction'] ?>')" class="btn btn-sm btn-danger">
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
   function deleteTransaksi(id) {
      if (confirm('Yakin ingin menghapus transaksi ini?')) {
         jQuery.ajax({
            url: "<?= base_url('admin/kasir/transaksi/delete/') ?>" + id,
            type: 'DELETE',
            success: function(response) {
               getDataTransaksi();
            },
            error: function() {
               alert('Gagal menghapus transaksi');
            }
         });
      }
   }
</script>
