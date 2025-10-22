<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="card">
         <div class="card-body">
            <div class="pt-3 pl-3 pb-2">
               <h4><b>Tanggal</b></h4>
               <input class="form-control" type="date" name="tangal" id="tanggal" value="<?= date('Y-m-d'); ?>" onchange="getPegawai()" style="max-width: 200px;">
            </div>
         </div>
      </div>
      <div class="card primary">
         <div class="card-body">
            <div class="row justify-content-between">
               <div class="col">
                  <div class="pt-3 pl-3">
                     <h4><b>Absen Pegawai </b></h4>
                     <p>Daftar pegawai muncul disini</p>
                  </div>
               </div>
               <div class="col-sm-auto">
                  <a href="#" class="btn btn-success pl-3 mr-3 mt-3" onclick="di = getPegawai()" data-toggle="tab">
                     <i class="material-icons mr-2">refresh</i> Refresh
                  </a>
               </div>
            </div>

            <div id="dataPegawai">

            </div>
         </div>
      </div>
   </div>

   <!-- Modal -->
   <div class="modal fade" id="ubahModal" tabindex="-1" aria-labelledby="modalUbahKehadiran" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="modalUbahKehadiran">Ubah kehadiran</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div id="modalFormUbahPegawai"></div>
         </div>
      </div>
   </div>
</div>
<script>
   getPegawai();

   function getPegawai() {
      var tanggal = $('#tanggal').val();

      jQuery.ajax({
         url: "<?= base_url('/admin/absen-pegawai'); ?>",
         type: 'post',
         data: {
            'tanggal': tanggal
         },
         success: function(response, status, xhr) {
            // console.log(status);
            $('#dataPegawai').html(response);

            $('html, body').animate({
               scrollTop: $("#dataPegawai").offset().top
            }, 500);
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            $('#dataPegawai').html(thrown);
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

      jQuery.ajax({
         url: "<?= base_url('/admin/absen-pegawai/edit'); ?>",
         type: 'post',
         data: form,
         success: function(response, status, xhr) {
            // console.log(status);

            if (response['status']) {
               alert('Berhasil ubah kehadiran : ' + response['nama_pegawai']);
            } else {
               alert('Gagal ubah kehadiran : ' + response['nama_pegawai']);
            }

            getPegawai();
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            alert('Gagal ubah kehadiran\n' + thrown);
         }
      });
   }

   function getDataKehadiran(idPresensi, idPegawai) {
      jQuery.ajax({
         url: "<?= base_url('/admin/absen-pegawai/kehadiran'); ?>",
         type: 'post',
         data: {
            'id_presensi': idPresensi,
            'id_pegawai': idPegawai
         },
         success: function(response, status, xhr) {
            // console.log(status);
            $('#modalFormUbahPegawai').html(response);
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            $('#modalFormUbahPegawai').html(thrown);
         }
      });
   }

   function deletePresensi(idPresensi, type) {
      if (confirm('Apakah Anda yakin ingin menghapus presensi ini?')) {
         jQuery.ajax({
            url: "<?= base_url('/admin/absen-pegawai/delete'); ?>",
            type: 'post',
            data: {
               'id_presensi': idPresensi
            },
            success: function(response, status, xhr) {
               if (response['status']) {
                  alert(response['message']);
                  getPegawai();
               } else {
                  alert(response['message']);
               }
            },
            error: function(xhr, status, thrown) {
               console.log(thrown);
               alert('Gagal menghapus presensi\n' + thrown);
            }
         });
      }
   }
</script>
<?= $this->endSection() ?>