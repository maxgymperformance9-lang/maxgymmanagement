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
                                 <h4 class="card-title"><b>Daftar Kelas Fitness</b></h4>
                                 <p class="card-category"> MAXGYM  <?= is_array($generalSettings) ? $generalSettings['office_year'] : $generalSettings->office_year; ?></p>
                              </div>
                              <div class="ml-md-auto col-auto row">
                                 <div class="col-12 col-sm-auto nav nav-tabs">
                                    <div class="nav-item">
                                       <a class="nav-link" id="tabBtn" onclick="removeHover()" href="<?= base_url('admin/fitness-classes/create'); ?>">
                                          <i class="material-icons">add</i> Tambah kelas
                                          <div class="ripple-container"></div>
                                       </a>
                                    </div>
                                 </div>
                                 <div class="col-12 col-sm-auto nav nav-tabs">
                                    <div class="nav-item">
                                       <a class="nav-link" id="refreshBtn" onclick="getDataClasses()" href="#" data-toggle="tab">
                                          <i class="material-icons">refresh</i> Refresh
                                          <div class="ripple-container"></div>
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                <div id="dataClasses">
                  <?php if (isset($classes)) : ?>
                     <?= view('admin/fitness/list-classes', ['classes' => $classes]) ?>
                  <?php else: ?>
                     <p class="text-center mt-3">Daftar kelas fitness muncul disini</p>
                  <?php endif; ?>
                </div>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
</div>
<script>
   getDataClasses();

   function getDataClasses() {
      jQuery.ajax({
         url: "<?= base_url('/admin/fitness-classes/ajax-list'); ?>",
         type: 'post',
         data: {},
         success: function(response, status, xhr) {
            $('#dataClasses').html(response);
            $('html, body').animate({
               scrollTop: $("#dataClasses").offset().top
            }, 500);
            $('#refreshBtn').removeClass('active show');
         },
         error: function(xhr, status, thrown) {
            console.log(thrown);
            $('#dataClasses').html(thrown);
            $('#refreshBtn').removeClass('active show');
         }
      });
   }

   function removeHover() {
      setTimeout(() => {
         $('#tabBtn').removeClass('active show');
      }, 250);
   }
</script>
<?= $this->endSection() ?>
