<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="card">
         <div class="card-header">
            <h4><b>Tambah Pengeluaran</b></h4>
         </div>
         <div class="card-body">
            <form id="formPengeluaran">
               <div class="form-group">
                  <label for="description">Deskripsi</label>
                  <input type="text" class="form-control" id="description" name="description" required>
               </div>
               <div class="form-group">
                  <label for="amount">Jumlah (Rp)</label>
                  <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" required>
               </div>
               <div class="form-group">
                  <label for="category">Kategori</label>
                  <select class="form-control" id="category" name="category" required>
                     <option value="">Pilih Kategori</option>
                     <option value="Operasional">Operasional</option>
                     <option value="Maintenance">Maintenance</option>
                     <option value="Gaji">Gaji</option>
                     <option value="Utilitas">Utilitas</option>
                     <option value="Pemasaran">Pemasaran</option>
                     <option value="Lainnya">Lainnya</option>
                  </select>
               </div>
               <div class="form-group">
                  <label for="expense_date">Tanggal Pengeluaran</label>
                  <input type="date" class="form-control" id="expense_date" name="expense_date" value="<?= date('Y-m-d'); ?>" required>
               </div>
               <button type="submit" class="btn btn-primary">Simpan</button>
               <a href="<?= base_url('admin/pengeluaran'); ?>" class="btn btn-secondary">Kembali</a>
            </form>
         </div>
      </div>
   </div>
</div>

<script>
   $('#formPengeluaran').on('submit', function(e) {
      e.preventDefault();

      jQuery.ajax({
         url: "<?= base_url('/admin/pengeluaran/store'); ?>",
         type: 'post',
         data: $(this).serialize(),
         success: function(response, status, xhr) {
            if (response['status']) {
               alert(response['message']);
               window.location.href = "<?= base_url('admin/pengeluaran'); ?>";
            } else {
               alert('Gagal: ' + response['message']);
               if (response['errors']) {
                  console.log(response['errors']);
               }
            }
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            alert('Gagal menyimpan pengeluaran\n' + thrown);
         }
      });
   });
</script>
<?= $this->endSection() ?>
