<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="card">
         <div class="card-body">
            <div class="pt-3 pl-3 pb-2">
               <h4><b>Bulan</b></h4>
               <input class="form-control" type="month" name="bulan" id="bulan" value="<?= date('Y-m'); ?>" onchange="ambilDataPengeluaran()" style="max-width: 200px;">
            </div>
         </div>
      </div>
      <div class="card primary">
         <div class="card-body">
            <div class="row justify-content-between">
               <div class="col">
                  <div class="pt-3 pl-3">
                     <h4><b>Data Pengeluaran</b></h4>
                     <p>Daftar pengeluaran muncul disini</p>
                  </div>
               </div>
               <div class="col-sm-auto">
                  <a href="<?= base_url('admin/pengeluaran/create'); ?>" class="btn btn-success pl-3 mr-3 mt-3">
                     <i class="material-icons mr-2">add</i> Tambah Pengeluaran
                  </a>
               </div>
            </div>

            <div id="dataPengeluaran">

            </div>
         </div>
      </div>
   </div>
</div>
<script>
   ambilDataPengeluaran();

   function ambilDataPengeluaran() {
      var bulan = $('#bulan').val();

      jQuery.ajax({
         url: "<?= base_url('/admin/pengeluaran/data'); ?>",
         type: 'post',
         data: {
            'bulan': bulan
         },
         success: function(response, status, xhr) {
            $('#dataPengeluaran').html(response);
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            $('#dataPengeluaran').html(thrown);
         }
      });
   }
</script>
<?= $this->endSection() ?>
