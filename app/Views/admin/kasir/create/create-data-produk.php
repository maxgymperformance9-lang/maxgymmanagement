<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-8">
            <div class="card">
               <div class="card-header card-header-primary">
                  <h4 class="card-title"><?= $title ?></h4>
                  <p class="card-category">Form tambah data produk</p>
               </div>
               <div class="card-body">
                  <form action="<?= base_url('admin/produk/create') ?>" method="post">
                     <?= csrf_field() ?>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group">
                              <label class="bmd-label-floating">Nama Produk</label>
                              <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('nama_produk')) ? 'is-invalid' : '' ?>" name="nama_produk" value="<?= old('nama_produk') ?>" required>
                              <?php if (isset($validation) && $validation->hasError('nama_produk')): ?>
                                 <div class="invalid-feedback"><?= $validation->showError('nama_produk') ?></div>
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
                              <label class="bmd-label-floating">Stok</label>
                              <input type="number" class="form-control <?= (isset($validation) && $validation->hasError('stok')) ? 'is-invalid' : '' ?>" name="stok" value="<?= old('stok') ?>" required>
                              <?php if (isset($validation) && $validation->hasError('stok')): ?>
                                 <div class="invalid-feedback"><?= $validation->showError('stok') ?></div>
                              <?php endif; ?>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="bmd-label-floating">Kategori</label>
                              <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('kategori')) ? 'is-invalid' : '' ?>" name="kategori" value="<?= old('kategori') ?>">
                              <?php if (isset($validation) && $validation->hasError('kategori')): ?>
                                 <div class="invalid-feedback"><?= $validation->showError('kategori') ?></div>
                              <?php endif; ?>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="bmd-label-floating">Status</label>
                              <select class="form-control" name="status" required>
                                 <option value="active" <?= old('status') == 'active' ? 'selected' : '' ?>>Active</option>
                                 <option value="inactive" <?= old('status') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
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
                     <div class="clearfix"></div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
