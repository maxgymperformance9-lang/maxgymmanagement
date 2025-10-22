<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <?php if (session()->getFlashdata('msg')) : ?>
               <div class="pb-2 px-3">
                  <div class="alert alert-<?= session()->getFlashdata('error') == true ? 'danger' : 'success' ?>">
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="material-icons">close</i>
                     </button>
                     <?= session()->getFlashdata('msg') ?>
                  </div>
               </div>
            <?php endif; ?>

            <div class="row">
               <div class="col-12 col-xl-12">
                  <div class="card">
                     <div class="card-header card-header-tabs card-header-success">
                        <div class="nav-tabs-navigation">
                           <div class="row">
                              <div class="col-md-4 col-lg-5">
                                 <h4 class="card-title"><b>Daftar Member</b></h4>
                                 <p class="card-category">MAXGYM <?= esc($generalSettings->office_year); ?></p>
                              </div>
                              <div class="ml-md-auto col-auto row">
                                 <div class="col-12 col-sm-auto nav nav-tabs">
                                    <div class="nav-item">
                                       <a class="nav-link" id="tabBtn" onclick="removeHover()" href="<?= base_url('admin/member/create'); ?>">
                                          <i class="material-icons">add</i> Tambah data member
                                          <div class="ripple-container"></div>
                                       </a>
                                    </div>
                                 </div>
                                 <div class="col-12 col-sm-auto nav nav-tabs">
                                    <div class="nav-item">
                                       <a class="nav-link" id="refreshBtn" onclick="getDataMember()" href="#" data-toggle="tab">
                                          <i class="material-icons">refresh</i> Refresh
                                          <div class="ripple-container"></div>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div id="dataMember">
                        <p class="text-center mt-3">Daftar member muncul disini...</p>
                     </div>

                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>

<!-- Modal Upload Foto -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="uploadTitle">Upload Foto Member</h5>
            <button type="button" class="close" data-dismiss="modal">
               <span>&times;</span>
            </button>
         </div>
         <form id="uploadForm" method="post" enctype="multipart/form-data">
            <div class="modal-body">
               <div class="form-group">
                  <label for="foto">Pilih Foto</label>
                  <input type="file" class="form-control" name="foto" accept="image/*" required>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-primary">Upload</button>
            </div>
         </form>
      </div>
   </div>
</div>

<script>
   // Muat data member
   getDataMember();

   function getDataMember() {
      $.ajax({
         url: "<?= base_url('/admin/member'); ?>",
         type: 'post',
         success: function(response) {
            $('#dataMember').html(response);
            $('html, body').animate({
               scrollTop: $("#dataMember").offset().top
            }, 500);
            $('#refreshBtn').removeClass('active show');
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            $('#dataMember').html('<p class="text-danger text-center">Gagal memuat data member.</p>');
         }
      });
   }

   // Fungsi untuk membuka modal upload foto
   function uploadFoto(id, nama) {
      $('#uploadTitle').text('Upload Foto - ' + nama);
      $('#uploadForm').attr('action', "<?= base_url('admin/member/upload-foto/'); ?>" + id);
      $('#uploadModal').modal('show');
   }

   // Fungsi untuk mengirim email welcome
   function sendWelcomeEmail(id, nama) {
      if (confirm('Apakah Anda yakin ingin mengirim email welcome ke ' + nama + '?')) {
         $.ajax({
            url: "<?= base_url('admin/member/send-welcome-email/'); ?>" + id,
            type: 'post',
            data: {
               '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
               var data = JSON.parse(response);
               if (data.success) {
                  alert(data.message);
               } else {
                  alert('Error: ' + data.message);
               }
            },
            error: function(xhr, status, thrown) {
               console.log(thrown);
               alert('Gagal mengirim email. Silakan coba lagi.');
            }
         });
      }
   }

   // Fungsi untuk mengirim WhatsApp welcome
   function sendWelcomeWhatsApp(id, nama) {
      if (confirm('Apakah Anda yakin ingin mengirim WhatsApp welcome ke ' + nama + '?')) {
         $.ajax({
            url: "<?= base_url('admin/member/send-welcome-whatsapp/'); ?>" + id,
            type: 'post',
            data: {
               '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
               var data = JSON.parse(response);
               if (data.success) {
                  alert(data.message);
               } else {
                  alert('Error: ' + data.message);
               }
            },
            error: function(xhr, status, thrown) {
               console.log(thrown);
               alert('Gagal mengirim WhatsApp. Silakan coba lagi.');
            }
         });
      }
   }

   // Hilangkan hover saat klik tab tambah data
   function removeHover() {
      setTimeout(() => {
         $('#tabBtn').removeClass('active show');
      }, 250);
   }
</script>
<?= $this->endSection() ?>
