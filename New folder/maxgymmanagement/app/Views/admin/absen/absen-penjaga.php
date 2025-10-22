<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-body">
                  <div class="row justify-content-between">
                     <div class="col">
                        <div class="pt-3 pl-3">
                           <h4><b>Daftar D.I/WIL</b></h4>
                           <p>Silakan pilih D.I/WIL</p>
                        </div>
                     </div>
                  </div>

                  <div class="card-body pt-1 px-3">
                     <div class="row">
                        <?php foreach ($di as $value) : ?>
                           <?php
                           $idDi = $value['id_di'];
                           $namadi =  $value['di'] . ' ' . $value['wilayah'];
                           ?>
                           <div class="col-md-3">
                              <button id="di-<?= $idDi; ?>" onclick="getPenjaga(<?= $idDi; ?>, '<?= $namadi; ?>')" class="btn btn-primary w-100">
                                 <?= $namadi; ?>
                              </button>
                           </div>
                        <?php endforeach; ?>
                     </div>
                  </div>

                  <div class="row">
                     <div class="col-md-3">
                        <div class="pt-3 pl-3 pb-2">
                           <h4><b>Tanggal</b></h4>
                           <input class="form-control" type="date" name="tangal" id="tanggal" value="<?= date('Y-m-d'); ?>" onchange="onDateChange()">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="card" id="dataPenjaga">
         <div class="card-body">
            <div class="row justify-content-between">
               <div class="col-auto me-auto">
                  <div class="pt-3 pl-3">
                     <h4><b>Absen Petugas</b></h4>
                     <p>Daftar Petugas muncul disini</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Modal ubah kehadiran -->
   <div class="modal fade" id="ubahModal" tabindex="-1" aria-labelledby="modalUbahKehadiran" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="modalUbahKehadiran">Ubah kehadiran</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div id="modalFormUbahPenjaga"></div>
         </div>
      </div>
   </div>
</div>
<script>
   var lastIdDi;
   var lastDi;

   function onDateChange() {
      if (lastIdDi != null && lastDi != null) getPenjaga(lastIdDi, lastDi);
   }

   function getPenjaga(idDi, di) {
      var tanggal = $('#tanggal').val();

      updateBtn(idDi);

      jQuery.ajax({
         url: "<?= base_url('/admin/absen-penjaga'); ?>",
         type: 'post',
         data: {
            'di': di,
            'id_di': idDi,
            'tanggal': tanggal
         },
         success: function(response, status, xhr) {
            // console.log(status);
            $('#dataPenjaga').html(response);

            $('html, body').animate({
               scrollTop: $("#dataPenjaga").offset().top
            }, 500);
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            $('#dataPenjaga').html(thrown);
         }
      });

      lastIdDi = idDi;
      lastDi = di;
   }

   function updateBtn(id_btn) {
      for (let index = 1; index <= <?= count($di); ?>; index++) {
         if (index != id_btn) {
            $('#di-' + index).removeClass('btn-success');
            $('#di-' + index).addClass('btn-primary');
         } else {
            $('#di-' + index).removeClass('btn-primary');
            $('#di-' + index).addClass('btn-success');
         }
      }
   }

   function getDataKehadiran(idPresensi, idPenjaga) {
      jQuery.ajax({
         url: "<?= base_url('/admin/absen-penjagakehadiran'); ?>",
         type: 'post',
         data: {
            'id_presensi': idPresensi,
            'id_penjaga': idPenjaga
         },
         success: function(response, status, xhr) {
            // console.log(status);
            $('#namadimodalFormUbahPenjaga').html(response);
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            $('#namadimodalFormUbahPenjaga').html(thrown);
         }
      });
   }

   function ubahKehadiran() {
      var tanggal = $('#tanggal').val();

      var form = $('#formUbah').serializeArray();

      form.push({
         name: 'tanggal',
         value: tanggal
      });

      console.log(form);

      jQuery.ajax({
         url: "<?= base_url('/admin/absen-penjaga/edit'); ?>",
         type: 'post',
         data: form,
         success: function(response, status, xhr) {
            // console.log(status);

            if (response['status']) {
               getPenjaga(lastIdDi, lastDi);
               alert('Berhasil ubah kehadiran : ' + response['nama_penjaga']);
            } else {
               alert('Gagal ubah kehadiran : ' + response['nama_penjaga']);
            }
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            alert('Gagal ubah kehadiran\n' + thrown);
         }
      });
   }
</script>
<?= $this->endSection() ?>