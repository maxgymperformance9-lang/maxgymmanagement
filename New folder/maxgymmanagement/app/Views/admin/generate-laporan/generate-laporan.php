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
            <div class="card">
               <div class="card-header card-header-tabs card-header-info">
                  <div class="nav-tabs-navigation">
                     <div class="row">
                        <div class="col">
                           <h4 class="card-title"><b>Generate Laporan</b></h4>
                           <p class="card-category">Laporan absen</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="card-body">

                  <div class="row">
                     <div class="col-md-6">
                        <div class="card h-100">
                           <form action="<?= base_url('admin/laporan/penjaga'); ?>" method="post" class="card-body d-flex flex-column">
                              <h4 class="text-primary"><b>Laporan Absen Penjaga</b></h4>
                              <div class="row align-items-center">
                                 <div class="col-auto">
                                    <p class="d-inline"><b>Bulan :</b></p>
                                 </div>
                                 <div class="col-5">
                                    <input type="month" name="tanggalPenjaga" id="tanggalPenjaga" class="form-control" value="<?= date('Y-m'); ?>">
                                 </div>
                              </div>
                              <select name="di" class="custom-select mt-3">
                                 <option value="">--Pilih D.I/WIL--</option>
                                 <?php foreach ($di as $key => $value) : ?>
                                    <?php
                                    $idDi = $value['id_di'];
                                    $di = "{$value['di']} {$value['wilayah']}";
                                    $jumlahPenjaga = count($PenjagaPerDi[$key]);
                                    ?>
                                    <option value="<?= $idDi; ?>">
                                       <?= "$di - {$jumlahPenjaga} penjaga"; ?>
                                    </option>
                                 <?php endforeach; ?>
                              </select>
                              <div class="errMsg"></div>
                              <div class="mt-auto d-flex flex-column">
                                 <button type="submit" name="type" value="pdf" class="btn btn-danger pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">print</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate pdf</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                                 <button type="submit" name="type" value="doc" class="btn btn-info pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">description</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate doc</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                                 <!-- <button type="submit" name="type" value="xls" class="btn btn-success pl-3 mt-auto">
                                 <div class="row align-items-center">
                                    <div class="col-auto">
                                       <i class="material-icons" style="font-size: 32px;">table_view</i>
                                    </div>
                                    <div class="col">
                                       <div class="text-start">
                                          <h4 class="d-inline"><b>Generate xls</b></h4>
                                       </div>
                                    </div>
                                 </div>
                              </button> -->
                              </div>

                           </form>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="card h-100">
                           <form action="<?= base_url('admin/laporan/pegawai'); ?>" method="post" class="card-body d-flex flex-column">
                              <h4 class="text-success"><b>Laporan Absen Pegawai</b></h4>
                              <p>Total jumlah Pegawai : <b><?= count($pegawai); ?></b></p>
                              <div class="row align-items-center">
                                 <div class="col-auto">
                                    <p class="d-inline"><b>Bulan :</b></p>
                                 </div>
                                 <div class="col-5">
                                    <input type="month" name="tanggalPegawai" id="tanggalPegawai" class="form-control" value="<?= date('Y-m'); ?>">
                                 </div>
                              </div>
                              <div class="mt-auto d-flex flex-column">
                                 <button type="submit" name="type" value="pdf" class="btn btn-danger pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">print</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate pdf</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                                 <button type="submit" name="type" value="doc" class="btn btn-info pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">description</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate doc</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
                  <br><br>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="card h-100">
                           <form action="<?= base_url('admin/laporan/absensiMember'); ?>" method="post" class="card-body d-flex flex-column">
                              <h4 class="text-warning"><b>Laporan Absen Member</b></h4>
                              <div class="row align-items-center">
                                 <div class="col-auto">
                                    <p class="d-inline"><b>Bulan :</b></p>
                                 </div>
                                 <div class="col-5">
                                    <input type="month" name="tanggalMember" id="tanggalMember" class="form-control" value="<?= date('Y-m'); ?>">
                                 </div>
                              </div>
                              <div class="mt-auto d-flex flex-column">
                                 <button type="submit" name="type" value="pdf" class="btn btn-danger pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">print</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate pdf</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                                 <button type="submit" name="type" value="doc" class="btn btn-info pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">description</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate doc</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                              </div>
                           </form>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="card h-100">
                           <form action="<?= base_url('admin/laporan/absensiMemberPT'); ?>" method="post" class="card-body d-flex flex-column">
                              <h4 class="text-primary"><b>Laporan Absen Member PT</b></h4>
                              <div class="row align-items-center">
                                 <div class="col-auto">
                                    <p class="d-inline"><b>Bulan :</b></p>
                                 </div>
                                 <div class="col-5">
                                    <input type="month" name="tanggalMemberPT" id="tanggalMemberPT" class="form-control" value="<?= date('Y-m'); ?>">
                                 </div>
                              </div>
                              <div class="mt-auto d-flex flex-column">
                                 <button type="submit" name="type" value="pdf" class="btn btn-danger pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">print</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate pdf</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                                 <button type="submit" name="type" value="doc" class="btn btn-info pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">description</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate doc</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
                  <br><br>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="card h-100">
                           <form action="<?= base_url('admin/laporan/dataMember'); ?>" method="post" class="card-body d-flex flex-column">
                              <h4 class="text-info"><b>Laporan Data Member</b></h4>
                              <p>Laporan data semua member</p>
                              <div class="mt-auto d-flex flex-column">
                                 <button type="submit" name="type" value="pdf" class="btn btn-danger pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">print</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate pdf</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                                 <button type="submit" name="type" value="doc" class="btn btn-info pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">description</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate doc</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                              </div>
                           </form>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="card h-100">
                           <form action="<?= base_url('admin/laporan/transaksi'); ?>" method="post" class="card-body d-flex flex-column">
                              <h4 class="text-secondary"><b>Laporan Transaksi</b></h4>
                              <div class="row align-items-center">
                                 <div class="col-auto">
                                    <p class="d-inline"><b>Dari :</b></p>
                                 </div>
                                 <div class="col-5">
                                    <input type="date" name="startDate" id="startDate" class="form-control" value="<?= date('Y-m-01'); ?>">
                                 </div>
                              </div>
                              <div class="row align-items-center mt-2">
                                 <div class="col-auto">
                                    <p class="d-inline"><b>Sampai :</b></p>
                                 </div>
                                 <div class="col-5">
                                    <input type="date" name="endDate" id="endDate" class="form-control" value="<?= date('Y-m-t'); ?>">
                                 </div>
                              </div>
                              <div class="mt-auto d-flex flex-column">
                                 <button type="submit" name="type" value="pdf" class="btn btn-danger pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">print</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate pdf</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                                 <button type="submit" name="type" value="doc" class="btn btn-info pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">description</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate doc</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
                  <br><br>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="card h-100">
                           <form action="<?= base_url('admin/laporan/keuangan'); ?>" method="post" class="card-body d-flex flex-column">
                              <h4 class="text-dark"><b>Laporan Keuangan</b></h4>
                              <div class="row align-items-center">
                                 <div class="col-auto">
                                    <p class="d-inline"><b>Bulan :</b></p>
                                 </div>
                                 <div class="col-5">
                                    <input type="month" name="bulanKeuangan" id="bulanKeuangan" class="form-control" value="<?= date('Y-m'); ?>">
                                 </div>
                              </div>
                              <div class="mt-auto d-flex flex-column">
                                 <button type="submit" name="type" value="pdf" class="btn btn-danger pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">print</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate pdf</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                                 <button type="submit" name="type" value="doc" class="btn btn-info pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">description</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate doc</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                              </div>
                           </form>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="card h-100">
                           <form action="<?= base_url('admin/laporan/keseluruhan'); ?>" method="post" class="card-body d-flex flex-column">
                              <h4 class="text-primary"><b>Laporan Keseluruhan</b></h4>
                              <p>Gabungan semua laporan</p>
                              <div class="row align-items-center">
                                 <div class="col-auto">
                                    <p class="d-inline"><b>Jenis :</b></p>
                                 </div>
                                 <div class="col-5">
                                    <select name="jenisLaporan" id="jenisLaporan" class="form-control">
                                       <option value="harian">Harian</option>
                                       <option value="bulanan">Bulanan</option>
                                       <option value="tahunan">Tahunan</option>
                                    </select>
                                 </div>
                              </div>
                              <div id="inputContainer" class="mt-3">
                                 <div class="row align-items-center">
                                    <div class="col-auto">
                                       <p class="d-inline"><b>Tanggal :</b></p>
                                    </div>
                                    <div class="col-5">
                                       <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>">
                                    </div>
                                 </div>
                              </div>
                              <div class="mt-auto d-flex flex-column">
                                 <button type="submit" name="type" value="pdf" class="btn btn-danger pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">print</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate pdf</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                                 <button type="submit" name="type" value="doc" class="btn btn-info pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">description</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Generate doc</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                                 <button type="submit" name="type" value="view" class="btn btn-success pl-3">
                                    <div class="row align-items-center">
                                       <div class="col-auto">
                                          <i class="material-icons" style="font-size: 32px;">visibility</i>
                                       </div>
                                       <div class="col">
                                          <div class="text-start">
                                             <h4 class="d-inline"><b>Lihat Laporan</b></h4>
                                          </div>
                                       </div>
                                    </div>
                                 </button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
                  <br><br>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   document.getElementById('jenisLaporan').addEventListener('change', function() {
      const jenis = this.value;
      const inputContainer = document.getElementById('inputContainer');

      if (jenis === 'harian') {
         inputContainer.innerHTML = `
            <div class="row align-items-center">
               <div class="col-auto">
                  <p class="d-inline"><b>Tanggal :</b></p>
               </div>
               <div class="col-5">
                  <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>">
               </div>
            </div>
         `;
      } else if (jenis === 'bulanan') {
         inputContainer.innerHTML = `
            <div class="row align-items-center">
               <div class="col-auto">
                  <p class="d-inline"><b>Bulan :</b></p>
               </div>
               <div class="col-5">
                  <input type="month" name="bulan" id="bulan" class="form-control" value="<?= date('Y-m'); ?>">
               </div>
            </div>
         `;
      } else if (jenis === 'tahunan') {
         inputContainer.innerHTML = `
            <div class="row align-items-center">
               <div class="col-auto">
                  <p class="d-inline"><b>Tahun :</b></p>
               </div>
               <div class="col-5">
                  <input type="number" name="tahun" id="tahun" class="form-control" value="<?= date('Y'); ?>" min="2020" max="2030">
               </div>
            </div>
         `;
      }
   });
</script>
<?= $this->endSection() ?>
