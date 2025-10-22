<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header card-header-primary">
                  <h4 class="card-title">Generate Semua Laporan</h4>
                  <p class="card-category">Unduh semua laporan dalam format PDF dan Word</p>
               </div>
               <div class="card-body">
                  <form action="<?= base_url('admin/laporan/generate-all') ?>" method="post" target="_blank">
                     <?= csrf_field() ?>
                     <div class="form-group">
                        <label for="format">Pilih Format Laporan:</label>
                        <select class="form-control" name="format" id="format" required>
                           <option value="pdf">PDF</option>
                           <option value="word">Word</option>
                        </select>
                     </div>
                     <button type="submit" class="btn btn-success">Generate Semua Laporan</button>
                  </form>
                  <hr>
                  <p class="text-muted">Laporan yang digenerate meliputi: Absensi Penjaga, Absensi Pegawai, Absensi Member, Data Member, Transaksi, Keuangan, dan lainnya.</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
