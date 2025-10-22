<?php if ($empty): ?>
   <div class="card-body">
      <div class="row">
         <div class="col-sm-12 text-center">
            <h5 class="info-title">Tidak ada data</h5>
            <p class="card-category">Belum ada data produk</p>
         </div>
      </div>
   </div>
<?php else: ?>
   <div class="card-body">
      <div class="table-responsive">
         <table class="table table-hover">
            <thead class="text-primary">
               <th>No</th>
               <th>Nama Produk</th>
               <th>Harga</th>
               <th>Stok</th>
               <th>Deskripsi</th>
               <th>Aksi</th>
            </thead>
            <tbody>
               <?php $no = 1; foreach ($data as $row): ?>
                  <tr>
                     <td><?= $no++ ?></td>
                     <td><?= esc($row['nama_produk']) ?></td>
                     <td><?= number_format($row['harga'], 0, ',', '.') ?> IDR</td>
                     <td><?= $row['stok'] ?></td>
                     <td><?= esc($row['deskripsi'] ?? '-') ?></td>
                     <td>
                        <a href="<?= base_url('admin/produk/edit/' . $row['id_product']) ?>" class="btn btn-sm btn-primary">
                           <i class="material-icons">edit</i>
                        </a>
                        <button onclick="deleteProduk(<?= $row['id_product'] ?>)" class="btn btn-sm btn-danger">
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
   function deleteProduk(id) {
      if (confirm('Yakin ingin menghapus data ini?')) {
         jQuery.ajax({
            url: "<?= base_url('admin/produk/delete/') ?>" + id,
            type: 'DELETE',
            success: function(response) {
               getDataProduk();
            },
            error: function() {
               alert('Gagal menghapus data');
            }
         });
      }
   }
</script>
