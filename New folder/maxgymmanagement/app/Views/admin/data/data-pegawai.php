<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <?php if (session()->getFlashdata('msg')) : ?>
               <div class="pb-2 px-3">
                  <div class="alert alert-<?= session()->getFlashdata('error') == true ? 'danger' : 'success'  ?> ">
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
                                 <h4 class="card-title"><b>Daftar Pegawai</b></h4>
                                 <p class="card-category"> MAXGYM  <?= $generalSettings->office_year; ?></p>
                              </div>
                              <div class="ml-md-auto col-auto row">
                                 <div class="col-12 col-sm-auto nav nav-tabs">
                                    <div class="nav-item">
                                       <a class="nav-link" id="tabBtn" onclick="removeHover()" href="<?= base_url('admin/pegawai/create'); ?>">
                                          <i class="material-icons">add</i> Tambah data pegawai
                                          <div class="ripple-container"></div>
                                       </a>
                                    </div>
                                 </div>
                                 <div class="col-12 col-sm-auto nav nav-tabs">
                                    <div class="nav-item">
                                       <a class="nav-link" id="refreshBtn" onclick="getDataPegawai()" href="#" data-toggle="tab">
                                          <i class="material-icons">refresh</i> Refresh
                                          <div class="ripple-container"></div>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="dataPegawai">
                        <p class="text-center mt-3">Daftar pegawai muncul disini</p>
                     </div>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>
<script>
   getDataPegawai();

   function getDataPegawai() {
      jQuery.ajax({
         url: "<?= base_url('/admin/pegawai'); ?>",
         type: 'post',
         data: {},
         success: function(response, status, xhr) {
            // console.log(status);
            $('#dataPegawai').html(response);

            $('html, body').animate({
               scrollTop: $("#dataPegawai").offset().top
            }, 500);
            $('#refreshBtn').removeClass('active show');
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            $('#dataPegawai').html(thrown);
            $('#refreshBtn').removeClass('active show');
         }
      });
   }

   function uploadFoto(id, nama) {
      // Create modal for photo upload
      var modalHtml = `
         <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title">Upload Foto - ${nama}</h5>
                     <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                     </button>
                  </div>
                  <form action="<?= base_url('admin/pegawai/upload-foto/'); ?>${id}" method="post" enctype="multipart/form-data">
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
      `;

      // Remove existing modal if any
      $('#uploadModal').remove();
      // Add modal to body
      $('body').append(modalHtml);
      // Show modal
      $('#uploadModal').modal('show');
   }

   function removeHover() {
      setTimeout(() => {
         $('#tabBtn').removeClass('active show');
      }, 250);
   }
</script>
<?= $this->endSection() ?>