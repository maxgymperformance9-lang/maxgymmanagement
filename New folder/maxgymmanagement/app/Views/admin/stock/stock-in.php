<?= $this->extend('templates/admin_layout') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Barang Masuk</h4>
                        <p class="card-category">Catat barang yang masuk ke gudang</p>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/stock/in') ?>" method="post" id="stockInForm">
                            <?= csrf_field() ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Gudang *</label>
                                        <select name="id_warehouse" class="form-control" required>
                                            <option value="">Pilih Gudang</option>
                                            <?php foreach ($warehouses as $warehouse): ?>
                                                <option value="<?= $warehouse['id_warehouse'] ?>"
                                                        <?= old('id_warehouse') == $warehouse['id_warehouse'] ? 'selected' : '' ?>>
                                                    <?= $warehouse['nama_gudang'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['id_warehouse'])): ?>
                                            <span class="text-danger"><?= $errors['id_warehouse'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Produk *</label>
                                        <select name="id_product" class="form-control" required>
                                            <option value="">Pilih Produk</option>
                                            <?php foreach ($products as $product): ?>
                                                <option value="<?= $product['id_product'] ?>"
                                                        <?= old('id_product') == $product['id_product'] ? 'selected' : '' ?>>
                                                    <?= $product['nama_produk'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['id_product'])): ?>
                                            <span class="text-danger"><?= $errors['id_product'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Quantity *</label>
                                        <input type="number" name="quantity" class="form-control" min="1" required
                                               value="<?= old('quantity') ?>">
                                        <?php if (isset($errors['quantity'])): ?>
                                            <span class="text-danger"><?= $errors['quantity'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Referensi</label>
                                        <input type="text" name="reference" class="form-control"
                                               value="<?= old('reference') ?>" placeholder="No. PO, Invoice, dll">
                                        <?php if (isset($errors['reference'])): ?>
                                            <span class="text-danger"><?= $errors['reference'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">Catatan</label>
                                        <textarea name="notes" class="form-control" rows="3"
                                                  placeholder="Catatan tambahan"><?= old('notes') ?></textarea>
                                        <?php if (isset($errors['notes'])): ?>
                                            <span class="text-danger"><?= $errors['notes'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary pull-right">
                                        <i class="material-icons">save</i> Catat Barang Masuk
                                    </button>
                                    <a href="<?= base_url('admin/stock') ?>" class="btn btn-secondary pull-right mr-2">
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
