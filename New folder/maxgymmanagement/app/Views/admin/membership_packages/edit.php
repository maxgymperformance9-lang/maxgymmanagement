<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title"><b>Edit Membership Package</b></h4>
                    </div>
                    <div class="card-body mx-5 my-3">

                        <?php if (session()->getFlashdata('msg')) : ?>
                            <div class="pb-2">
                                <div class="alert alert-<?= session()->getFlashdata('error') == true ? 'danger' : 'success'  ?> ">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <i class="material-icons">close</i>
                                    </button>
                                    <?= session()->getFlashdata('msg') ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('admin/membership-packages/update/' . $package['id_package']) ?>" method="post">
                            <?= csrf_field() ?>
                            <?php $validation = \Config\Services::validation(); ?>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mt-4">
                                        <label for="nama_package">Package Name</label>
                                        <input type="text" id="nama_package" class="form-control <?= $validation->getError('nama_package') ? 'is-invalid' : ''; ?>" name="nama_package" placeholder="Package Name" value="<?= old('nama_package') ?? $package['nama_package'] ?>" required>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('nama_package'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mt-4">
                                        <label for="harga">Price (Rp)</label>
                                        <input type="number" id="harga" name="harga" class="form-control <?= $validation->getError('harga') ? 'is-invalid' : ''; ?>" placeholder="0" min="0" step="1000" value="<?= old('harga') ?? $package['harga'] ?>" required>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('harga'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mt-4">
                                        <label for="durasi_hari">Duration (Days)</label>
                                        <input type="number" id="durasi_hari" name="durasi_hari" class="form-control <?= $validation->getError('durasi_hari') ? 'is-invalid' : ''; ?>" placeholder="30" min="1" value="<?= old('durasi_hari') ?? $package['durasi_hari'] ?>" required>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('durasi_hari'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mt-4">
                                        <label for="pt_sessions">PT Sessions (Optional)</label>
                                        <input type="number" id="pt_sessions" name="pt_sessions" class="form-control" placeholder="0" min="0" value="<?= old('pt_sessions') ?? $package['pt_sessions'] ?? 0 ?>">
                                        <small class="form-text text-muted">Number of personal training sessions included</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mt-4">
                                        <label>Package Features</label>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="unlimited_classes" value="1" <?= (old('unlimited_classes') ?? $package['unlimited_classes']) ? 'checked' : '' ?>>
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                                Unlimited Classes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="checkbox" name="locker_access" value="1" <?= (old('locker_access') ?? $package['locker_access']) ? 'checked' : '' ?>>
                                                <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                                Locker Access
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mt-4">
                                        <label for="deskripsi">Description (Optional)</label>
                                        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" placeholder="Package description..."><?= old('deskripsi') ?? $package['deskripsi'] ?? '' ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mt-4">
                                        <label for="benefits">Benefits</label>
                                        <textarea id="benefits" name="benefits" class="form-control" rows="3" placeholder="List the benefits of this package..."><?= old('benefits') ?? $package['benefits'] ?? '' ?></textarea>
                                        <small class="form-text text-muted">Describe what members get with this package (one benefit per line)</small>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-block">Update Package</button>
                        </form>

                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
