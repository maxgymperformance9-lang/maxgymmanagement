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
            <a class="btn btn-primary ml-3 pl-3 py-3" href="<?= base_url('admin/penjaga/create'); ?>">
               <i class="material-icons mr-2">add</i> Tambah data Penjaga
            </a>
            <a class="btn btn-primary ml-3 pl-3 py-3" href="<?= base_url('admin/penjaga/bulk'); ?>">
               <i class="material-icons mr-2">add</i> Import CSV
            </a>
            <button class="btn btn-danger ml-3 pl-3 py-3 btn-table-delete" onclick="deleteSelectedPenjaga('Data yang sudah dihapus tidak bisa kembalikan');"><i class="material-icons mr-2">delete_forever</i>Bulk Delete</button>
            <div class="card">
               <div class="card-header card-header-tabs card-header-primary">
                  <div class="nav-tabs-navigation">
                     <div class="row">
                        <div class="col-md-2">
                           <h4 class="card-title"><b>Daftar Penjaga</b></h4>
                           <p class="card-category">Angkatan <?= $generalSettings->office_year; ?></p>
                        </div>
                        <div class="col-md-4">
                           <div class="nav-tabs-wrapper">
                              <span class="nav-tabs-title">D.I/WIL:</span>
                              <ul class="nav nav-tabs" data-tabs="tabs">
                                 <li class="nav-item">
                                    <a class="nav-link active" onclick="di = null; trig()" href="#" data-toggle="tab">
                                       <i class="material-icons">check</i> Semua
                                       <div class="ripple-container"></div>
                                    </a>
                                 </li>
                                 <?php
                                 $tempDi = [];
                                 foreach ($di as $value) : ?>
                                    <?php if (!in_array($value['di'], $tempDi)) : ?>
                                       <li class="nav-item">
                                          <a class="nav-link" onclick="di = '<?= $value['di']; ?>'; trig()" href="#" data-toggle="tab">
                                             <i class="material-icons">Office</i> <?= $value['di']; ?>
                                             <div class="ripple-container"></div>
                                          </a>
                                       </li>
                                       <?php array_push($tempDi, $value['di']) ?>
                                    <?php endif; ?>
                                 <?php endforeach; ?>
                              </ul>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="nav-tabs-wrapper">
                              <span class="nav-tabs-title">Wilayah:</span>
                              <ul class="nav nav-tabs" data-tabs="tabs">
                                 <li class="nav-item">
                                    <a class="nav-link active" onclick="wilayah = null; trig()" href="#" data-toggle="tab">
                                       <i class="material-icons">check</i> Semua
                                       <div class="ripple-container"></div>
                                    </a>
                                 </li>
                                 <?php foreach ($wilayah as $value) : ?>
                                    <li class="nav-item">
                                       <a class="nav-link" onclick="wilayah = '<?= $value['wilayah']; ?>'; trig();" href="#" data-toggle="tab">
                                          <i class="material-icons">work</i> <?= $value['wilayah']; ?>
                                          <div class="ripple-container"></div>
                                       </a>
                                    </li>
                                 <?php endforeach; ?>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div id="dataPenjaga">
                  <p class="text-center mt-3">Daftar pegawai muncul disini</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   var di = null;
   var wilayah = null;

   getDataPenjaga(di, wilayah);

   function trig() {
      getDataPenjaga(di, wilayah);
   }

   function getDataPenjaga(_di = null, _wilayah = null) {
      jQuery.ajax({
         url: "<?= base_url('/admin/penjaga'); ?>",
         type: 'post',
         data: {
            'di': _di,
            'wilayah': _wilayah
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
   }

   document.addEventListener('DOMContentLoaded', function() {
      $("#checkAll").click(function(e) {
         console.log(e);
         $('input:checkbox').not(this).prop('checked', this.checked);
      });
   });
</script>
<?= $this->endSection() ?>