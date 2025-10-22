<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<style>
  .progress-penjaga {
    height: 5px;
    border-radius: 0px;
    background-color: rgb(186, 124, 222);
  }

  .progress-pegawai {
    height: 5px;
    border-radius: 0px;
    background-color: rgb(58, 192, 85);
  }

  .my-progress-bar {
    height: 5px;
    border-radius: 0px;
  }
</style>
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
          <div class="card-header card-header-danger">
            <h4 class="card-title"><b>Generate QR Code</b></h4>
            <p class="card-category">Generate QR berdasarkan kode unik data Petugas/Pegawai/Member</p>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h4 class="text-primary"><b>Data Penjaga</b></h4>
                    <p>Total jumlah Penjaga : <b><?= count($penjaga); ?></b>
                      <br>
                      <a href="<?= base_url('admin/petugas'); ?>">Lihat data</a>
                    </p>
                    <div class="row px-2">
                      <div class="col-12 col-xl-6 px-1">
                        <button onclick="generateAllQrPenjaga()" class="btn btn-primary p-2 px-md-4 w-100">
                          <div class="d-flex align-items-center justify-content-center" style="gap: 12px;">
                            <div>
                              <i class="material-icons" style="font-size: 24px;">qr_code</i>
                            </div>
                            <div>
                              <h4 class="d-inline font-weight-bold">Generate All</h4>
                              <div id="progressSiswa" class="d-none mt-2">
                                <span id="progressTextPenjaga"></span>
                                <i id="progressSelesaiPenjaga" class="material-icons d-none" class="d-none">check</i>
                                <div class="progress progress-penjaga">
                                  <div id="progressBarPenjaga" class="progress-bar my-progress-bar bg-white" style="width: 0%;" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax=""></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </button>
                      </div>
                      <div class="col-12 col-xl-6 px-1">
                        <a href="<?= base_url('admin/qr/petugas/download'); ?>" class="btn btn-primary p-2 px-md-4 w-100">
                          <div class="d-flex align-items-center justify-content-center" style="gap: 12px;">
                            <div>
                              <i class="material-icons" style="font-size: 24px;">cloud_download</i>
                            </div>
                            <div>
                              <div class="text-start">
                                <h4 class="d-inline font-weight-bold">Download All</h4>
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                    <hr>
                    <br>
                   <!-- <h4 class="text-primary"><b>Generate per D.I/WIL</b></h4>
                    <form action="<?= base_url('admin/qr/petugas/download'); ?>" method="get">
                      <select name="id_di" id="DiSelect" class="custom-select mb-3" required>
                        <option value="">--Pilih D.I/WIL--</option>
                        <?php foreach ($di as $value) : ?>
                          <option id="idDi<?= $value['id_di']; ?>" value="<?= $value['id_di']; ?>">
                            <?= $value['di'] . ' ' . $value['wilayah']; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                      <b class="text-danger mt-2" id="textErrorKelas"></b>
                      <div class="row px-2">
                        <div class="col-12 col-xl-6 px-1">
                          <button type="button" onclick="generateQrPenjagaByDi()" class="btn btn-primary p-2 px-md-4 w-100">
                            <div class="d-flex align-items-center justify-content-center" style="gap: 12px;">
                              <div>
                                <i class="material-icons" style="font-size: 24px;">qr_code</i>
                              </div>
                              <div>
                                <div class="text-start">
                                  <h6 class="d-inline">Generate per D.I/WIL</h6>
                                </div>
                                <div id="progressKelas" class="d-none">
                                  <span id="progressTextKelas"></span>
                                  <i id="progressSelesaiKelas" class="material-icons d-none" class="d-none">check</i>
                                  <div class="progress progress-penjaga d-none" id="progressBarBgKelas">
                                    <div id="progressBarKelas" class="progress-bar my-progress-bar bg-white" style="width: 0%;" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax=""></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </button>
                        </div> 
                        <div class="col-12 col-xl-6 px-1">
                          <button type="submit" class="btn btn-primary p-2 px-md-4 w-100">
                            <div class="d-flex align-items-center justify-content-center" style="gap: 12px;">
                              <div>
                                <i class="material-icons" style="font-size: 24px;">cloud_download</i>
                              </div>
                              <div>
                                <div class="text-start">
                                  <h6 class="d-inline">Download Per D.I/WIL</h6>
                                </div>
                              </div>
                            </div>
                          </button>
                        </div>
                      </div>
                    </form>
                    <br>-->
                    <p>
                      Untuk generate/download QR Code per masing-masing Petugas kunjungi
                      <a href="<?= base_url('admin/petugas'); ?>"><b>Data Petugas</b></a>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h4 class="text-success"><b>Data Pegawai</b></h4>
                    <p>Total jumlah Pegawai : <b><?= count($pegawai); ?></b>
                      <br>
                      <a href="<?= base_url('admin/pegawai'); ?>" class="text-success">Lihat data</a>
                    </p>
                    <div class="row px-2">
                      <div class="col-12 col-xl-6 px-1">
                        <button onclick="generateAllQrGuru()" class="btn btn-success p-2 px-md-4 w-100">
                          <div class="d-flex align-items-center justify-content-center" style="gap: 12px;">
                            <div>
                              <i class="material-icons" style="font-size: 24px;">qr_code</i>
                            </div>
                            <div>
                              <h4 class="d-inline font-weight-bold">Generate All</h4>
                              <div>
                                <div id="progressGuru" class="d-none mt-2">
                                  <span id="progressTextGuru"></span>
                                  <i id="progressSelesaiGuru" class="material-icons d-none" class="d-none">check</i>
                                  <div class="progress progress-pegawai">
                                    <div id="progressBarGuru" class="progress-bar my-progress-bar bg-white" style="width: 0%;" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax=""></div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </button>
                      </div>
                      <div class="col-12 col-xl-6 px-1">
                        <a href="<?= base_url('admin/qr/pegawai/download'); ?>" class="btn btn-success p-2 px-md-4 w-100">
                          <div class="d-flex align-items-center justify-content-center" style="gap: 12px;">
                            <div>
                              <i class="material-icons" style="font-size: 24px;">cloud_download</i>
                            </div>
                            <div>
                              <div class="text-start">
                                <h4 class="d-inline font-weight-bold">Download All</h4>
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                    <br>
                    <br>
                    <p>
                      Untuk generate/download QR Code per masing-masing Pegawai kunjungi
                      <a href="<?= base_url('admin/pegawai'); ?>" class="text-success"><b>Data Pegawai</b></a>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h4 class="text-info"><b>Data Member</b></h4>
                    <p>Total jumlah Member : <b><?= count($member); ?></b>
                      <br>
                      <a href="<?= base_url('admin/member'); ?>" class="text-info">Lihat data</a>
                    </p>
                    <div class="row px-2">
                      <div class="col-12 col-xl-6 px-1">
                        <button onclick="generateAllQrMember()" class="btn btn-info p-2 px-md-4 w-100">
                          <div class="d-flex align-items-center justify-content-center" style="gap: 12px;">
                            <div>
                              <i class="material-icons" style="font-size: 24px;">qr_code</i>
                            </div>
                            <div>
                              <h4 class="d-inline font-weight-bold">Generate All</h4>
                              <div id="progressMember" class="d-none mt-2">
                                <span id="progressTextMember"></span>
                                <i id="progressSelesaiMember" class="material-icons d-none" class="d-none">check</i>
                                <div class="progress progress-pegawai">
                                  <div id="progressBarMember" class="progress-bar my-progress-bar bg-white" style="width: 0%;" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax=""></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </button>
                      </div>
                      <div class="col-12 col-xl-6 px-1">
                        <a href="<?= base_url('admin/qr/member/download'); ?>" class="btn btn-info p-2 px-md-4 w-100">
                          <div class="d-flex align-items-center justify-content-center" style="gap: 12px;">
                            <div>
                              <i class="material-icons" style="font-size: 24px;">cloud_download</i>
                            </div>
                            <div>
                              <div class="text-start">
                                <h4 class="d-inline font-weight-bold">Download All</h4>
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                    <br>
                    <br>
                    <p>
                      Untuk generate/download QR Code per masing-masing Member kunjungi
                      <a href="<?= base_url('admin/member'); ?>" class="text-info"><b>Data Member</b></a>
                    </p>
                  </div>
                </div>
                <p class="text-danger">
                  <i class="material-icons" style="font-size: 16px;">warning</i>
                  File image QR Code tersimpan di [folder website]/public/uploads/
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  const dataPegawai = [
    <?php foreach ($pegawai as $value) {
      echo "{
              'nama' : `$value[nama_pegawai]`,
              'unique_code' : `$value[unique_code]`,
              'nomor' : `$value[nip]`
            },";
    }; ?>
  ];

  const dataMember = [
    <?php foreach ($member as $value) {
      echo "{
              'nama' : `$value[nama_member]`,
              'unique_code' : `$value[unique_code]`,
              'nomor' : `$value[no_hp]`
            },";
    }; ?>
  ];

  const dataPenjaga = [
    <?php foreach ($penjaga as $value) {
      echo "{
              'nama' : `$value[nama_penjaga]`,
              'unique_code' : `$value[unique_code]`,
              'id_di' : `$value[id_di]`,
              'nomor' : `$value[nip]`
            },";
    }; ?>
  ];

  var dataSiswaPerKelas = [];

  function generateAllQrPenjaga() {
    var i = 1;
    $('#progressSiswa').removeClass('d-none');
    $('#progressBarPenjaga')
      .attr('aria-valuenow', '0')
      .attr('aria-valuemin', '0')
      .attr('aria-valuemax', dataPenjaga.length)
      .attr('style', 'width: 0%;');

    dataPenjaga.forEach(element => {
      jQuery.ajax({
        url: "<?= base_url('admin/generate/petugas'); ?>",
        type: 'post',
        data: {
          nama: element['nama'],
          unique_code: element['unique_code'],
          id_di: element['id_di'],
          nomor: element['nomor']
        },
        success: function(response) {
          if (!response) return;
          if (i != dataPenjaga.length) {
            $('#progressTextSiswa').html('Progres: ' + i + '/' + dataPenjaga.length);
          } else {
            $('#progressTextSiswa').html('Progres: ' + i + '/' + dataPenjaga.length + ' selesai');
            $('#progressSelesaiPenjaga').removeClass('d-none');
          }

          $('#progressBarPenjaga')
            .attr('aria-valuenow', i)
            .attr('style', 'width: ' + (i / dataPenjaga.length) * 100 + '%;');
          i++;
        }
      });
    });
  }

  function generateQrPenjagaByDi() {
    var i = 1;

    idDi = $('#DiSelect').val();

    if (idDi == '') {
      $('#progressKelas').addClass('d-none');
      $('#textErrorKelas').html('Pilih D.I terlebih dahulu');
      return;
    }

    di = $('#idDi' + idDi).html();

    jQuery.ajax({
      url: "<?= base_url('admin/generate/penjaga-by-di'); ?>",
      type: 'post',
      data: {
        idDi: idDi
      },
      success: function(response) {
        dataSiswaPerKelas = response;

        if (dataSiswaPerKelas.length < 1) {
          $('#progressKelas').addClass('d-none');
          $('#textErrorKelas').html('Data Petugas D.I ' + di + ' tidak ditemukan');
          return;
        }

        $('#textErrorKelas').html('')

        $('#progressKelas').removeClass('d-none');
        $('#progressBarBgKelas')
          .removeClass('d-none');
        $('#progressBarKelas')
          .removeClass('d-none')
          .attr('aria-valuenow', '0')
          .attr('aria-valuemin', '0')
          .attr('aria-valuemax', dataSiswaPerKelas.length)
          .attr('style', 'width: 0%;');

        dataSiswaPerKelas.forEach(element => {
          jQuery.ajax({
            url: "<?= base_url('admin/generate/petugas'); ?>",
            type: 'post',
            data: {
              nama: element['nama_penjaga'],
              unique_code: element['unique_code'],
              id_di: element['id_di'],
              nomor: element['nip']
            },
            success: function(response) {
              if (!response) return;
              if (i != dataSiswaPerKelas.length) {
                $('#progressTextKelas').html('Progres: ' + i + '/' + dataSiswaPerKelas.length);
              } else {
                $('#progressTextKelas').html('Progres: ' + i + '/' + dataSiswaPerKelas.length + ' selesai');
                $('#progressSelesaiKelas').removeClass('d-none');
              }

              $('#progressBarKelas')
                .attr('aria-valuenow', i)
                .attr('style', 'width: ' + (i / dataSiswaPerKelas.length) * 100 + '%;');
              i++;
            },
            error: function(xhr, status, thrown) {
              console.error(xhr + status + thrown);
            }
          });
        });
      }
    });
  }

  function generateAllQrGuru() {
    var i = 1;
    $('#progressGuru').removeClass('d-none');
    $('#progressBarGuru')
      .attr('aria-valuenow', '0')
      .attr('aria-valuemin', '0')
      .attr('aria-valuemax', dataPegawai.length)
      .attr('style', 'width: 0%;');

    dataPegawai.forEach(element => {
      jQuery.ajax({
        url: "<?= base_url('admin/generate/pegawai'); ?>",
        type: 'post',
        data: {
          nama: element['nama'],
          unique_code: element['unique_code'],
          nomor: element['nomor']
        },
        success: function(response) {
          if (!response) return;
          if (i != dataPegawai.length) {
            $('#progressTextGuru').html('Progres: ' + i + '/' + dataPegawai.length);
          } else {
            $('#progressTextGuru').html('Progres: ' + i + '/' + dataPegawai.length + ' selesai');
            $('#progressSelesaiGuru').removeClass('d-none');
          }

          $('#progressBarGuru')
            .attr('aria-valuenow', i)
            .attr('style', 'width: ' + (i / dataPegawai.length) * 100 + '%;');
          i++;
        }
      });
    });
  }

  function generateAllQrMember() {
    var i = 1;
    $('#progressMember').removeClass('d-none');
    $('#progressBarMember')
      .attr('aria-valuenow', '0')
      .attr('aria-valuemin', '0')
      .attr('aria-valuemax', dataMember.length)
      .attr('style', 'width: 0%;');

    dataMember.forEach(element => {
      jQuery.ajax({
        url: "<?= base_url('admin/generate/member'); ?>",
        type: 'post',
        data: {
          nama: element['nama'],
          unique_code: element['unique_code'],
          nomor: element['nomor']
        },
        success: function(response) {
          if (!response) return;
          if (i != dataMember.length) {
            $('#progressTextMember').html('Progres: ' + i + '/' + dataMember.length);
          } else {
            $('#progressTextMember').html('Progres: ' + i + '/' + dataMember.length + ' selesai');
            $('#progressSelesaiMember').removeClass('d-none');
          }

          $('#progressBarMember')
            .attr('aria-valuenow', i)
            .attr('style', 'width: ' + (i / dataMember.length) * 100 + '%;');
          i++;
        }
      });
    });
  }
</script>
<?= $this->endSection() ?>