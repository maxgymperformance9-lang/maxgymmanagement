<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <?= view('admin/_messages'); ?>
        <div class="row">
          <div class="col-12 col-xl-6">
            <div class="card">
              <div class="card-header card-header-tabs card-header-primary">
                <div class="nav-tabs-navigation">
                  <div class="row">
                    <div class="col-md-4 col-lg-5">
                      <h4 class="card-title"><b>Daftar D.I/WIL</b></h4>
                      <p class="card-category">Tahun Berdiri <?= $generalSettings->office_year; ?></p>
                    </div>

                    <div class="col-auto row">
                      <div class="col-12 col-sm-auto nav nav-tabs">
                        <a class="btn-custom-tools" id="tabBtn" href="<?= base_url('admin/di/tambah'); ?>">
                          <i class="material-icons">add</i> Tambah data D.I
                          <div class="ripple-container"></div>
                        </a>

                      </div>
                      <div class="col-12 col-sm-auto nav nav-tabs">
                        <a class="btn-custom-tools" id="refreshBtn" onclick="officefetchDiWilayahData('di', '#dataDi')" href="javascript:void(0)">
                          <i class="material-icons">refresh</i> Refresh

                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-data" id="dataDi">
              </div>
            </div>
          </div>
          <div class="col-12 col-xl-6">
            <div class="card">
              <div class="card-header card-header-tabs card-header-primary">
                <div class="nav-tabs-navigation">
                  <div class="row">
                    <div class="col-md-4 col-lg-5">
                      <h4 class="card-title"><b>Daftar Wilayah</b></h4>
                      <p class="card-category">Tahun Berdiri <?= $generalSettings->office_year; ?></p>
                    </div>
                    <div class="col-auto row">
                      <div class="col-12 col-sm-auto nav nav-tabs">
                        <a class="btn-custom-tools" id="tabBtn" href="<?= base_url('admin/wilayah/tambah'); ?>">
                          <i class="material-icons">add</i> Tambah data Wilayah
                        </a>

                      </div>
                      <div class="col-12 col-sm-auto nav nav-tabs">
                        <a class="btn-custom-tools" id="refreshBtn2" onclick="officefetchDiWilayahData('wilayah', '#dataWilayah')" href="javascript:void(0)">
                          <i class="material-icons">refresh</i> Refresh

                        </a>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              <div class="card-data" id="dataWilayah">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    officefetchDiWilayahData('di', '#dataDi');
    officefetchDiWilayahData('wilayah', '#dataWilayah');
  });

  
</script>
<?= $this->endSection() ?>