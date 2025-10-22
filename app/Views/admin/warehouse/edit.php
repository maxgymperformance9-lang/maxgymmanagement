<?= $this->extend('templates/admin_layout') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Edit Gudang</h4>
                        <p class="card-category">Edit data gudang</p>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/warehouse/update/' . $warehouse['id_warehouse']) ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Nama Gudang *</label>
                                        <input type="text" name="nama_gudang" class="form-control" required
                                               value="<?= old('nama_gudang', $warehouse['nama_gudang']) ?>">
                                        <?php if (isset($errors['nama_gudang'])): ?>
                                            <span class="text-danger"><?= $errors['nama_gudang'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Lokasi</label>
                                        <textarea name="lokasi" class="form-control" rows="3"
                                                  placeholder="Masukkan alamat lokasi gudang"><?= old('lokasi', $warehouse['lokasi']) ?></textarea>
                                        <?php if (isset($errors['lokasi'])): ?>
                                            <span class="text-danger"><?= $errors['lokasi'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status *</label>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="status"
                                                       value="active" <?= old('status', $warehouse['status']) === 'active' ? 'checked' : '' ?>>
                                                Aktif
                                                <span class="circle">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="status"
                                                       value="inactive" <?= old('status', $warehouse['status']) === 'inactive' ? 'checked' : '' ?>>
                                                Tidak Aktif
                                                <span class="circle">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <?php if (isset($errors['status'])): ?>
                                            <span class="text-danger"><?= $errors['status'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary pull-right">
                                        <i class="material-icons">save</i> Update
                                    </button>
                                    <a href="<?= base_url('admin/warehouse') ?>" class="btn btn-secondary pull-right mr-2">
                                        <i class="material-icons">cancel</i> Batal
                                    </a>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
