<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-8">
            <div class="card">
               <div class="card-header card-header-primary">
                  <h4 class="card-title">Tambah Kelas Fitness</h4>
                  <p class="card-category">Form tambah data kelas fitness</p>
               </div>
               <div class="card-body">
                  <form action="<?= base_url('admin/fitness-classes/store') ?>" method="post">
                     <?= csrf_field() ?>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group">
                              <label class="bmd-label-floating">Nama Kelas</label>
                              <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('nama_class')) ? 'is-invalid' : '' ?>" name="nama_class" value="<?= old('nama_class') ?>" required>
                              <?php if (isset($validation) && $validation->hasError('nama_class')): ?>
                                 <div class="invalid-feedback"><?= $validation->showError('nama_class') ?></div>
                              <?php endif; ?>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="bmd-label-floating">Durasi (menit)</label>
                              <input type="number" class="form-control <?= (isset($validation) && $validation->hasError('durasi')) ? 'is-invalid' : '' ?>" name="durasi" value="<?= old('durasi') ?>" required>
                              <?php if (isset($validation) && $validation->hasError('durasi')): ?>
                                 <div class="invalid-feedback"><?= $validation->showError('durasi') ?></div>
                              <?php endif; ?>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="bmd-label-floating">Kapasitas (orang)</label>
                              <input type="number" class="form-control <?= (isset($validation) && $validation->hasError('kapasitas')) ? 'is-invalid' : '' ?>" name="kapasitas" value="<?= old('kapasitas') ?>" required>
                              <?php if (isset($validation) && $validation->hasError('kapasitas')): ?>
                                 <div class="invalid-feedback"><?= $validation->showError('kapasitas') ?></div>
                              <?php endif; ?>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="bmd-label-floating">Harga (IDR)</label>
                              <input type="number" step="0.01" class="form-control <?= (isset($validation) && $validation->hasError('harga')) ? 'is-invalid' : '' ?>" name="harga" value="<?= old('harga') ?>" required>
                              <?php if (isset($validation) && $validation->hasError('harga')): ?>
                                 <div class="invalid-feedback"><?= $validation->showError('harga') ?></div>
                              <?php endif; ?>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="bmd-label-floating">Status</label>
                              <select class="form-control" name="status" required>
                                 <option value="aktif" <?= old('status') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                 <option value="nonaktif" <?= old('status') == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group">
                              <label class="bmd-label-floating">Deskripsi</label>
                              <textarea class="form-control <?= (isset($validation) && $validation->hasError('deskripsi')) ? 'is-invalid' : '' ?>" name="deskripsi" rows="3"><?= old('deskripsi') ?></textarea>
                              <?php if (isset($validation) && $validation->hasError('deskripsi')): ?>
                                 <div class="invalid-feedback"><?= $validation->showError('deskripsi') ?></div>
                              <?php endif; ?>
                           </div>
                        </div>
                     </div>
                     <button type="submit" class="btn btn-primary pull-right">Simpan</button>
                     <a href="<?= base_url('admin/fitness-classes') ?>" class="btn btn-secondary pull-right mr-2">Kembali</a>
                     <div class="clearfix"></div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
