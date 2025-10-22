<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <?= view('admin/_messages'); ?>
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title"><b>Form Tambah D.I/WIL</b></h4>
          </div>
          <div class="card-body mx-5 my-3">

            <form action="<?= base_url('admin/di/tambahDiPost'); ?>" method="post">
              <?= csrf_field() ?>
              <div class="form-group mt-4">
                <label for="di">Kode</label>
                <input type="text" id="di" class="form-control <?= invalidFeedback('di') ? 'is-invalid' : ''; ?>" name="di" placeholder="'X', 'XI', '11'" , value="<?= old('di') ?>" required>
                <div class="invalid-feedback">
                  <?= invalidFeedback('di'); ?>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <label for="id_wilayah">Wilayah</label>
                  <select class="custom-select <?= invalidFeedback('id_wilayah') ? 'is-invalid' : ''; ?>" id="id_wilayah" name="id_wilayah">
                    <option value="">--Pilih Wilayah--</option>
                    <?php foreach ($wilayah as $value) : ?>
                      <option value="<?= $value['id']; ?>" <?= old('id_wilayah') == $value['id'] ? 'selected' : ''; ?>>
                        <?= $value['wilayah']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback">
                    <?= invalidFeedback('id_wilayah'); ?>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary mt-4">Simpan</button>
            </form>

            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>