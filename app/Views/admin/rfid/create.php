<?= $this->extend('templates/admin_layout') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Add New RFID Card</h4>
                        <p class="card-category">Create a new RFID card for a member</p>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('admin/rfid/store') ?>" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_uid">Card UID *</label>
                                        <input type="text" class="form-control" id="card_uid" name="card_uid" required
                                               value="<?= old('card_uid') ?>" placeholder="Enter RFID card UID">
                                        <?php if (isset($errors['card_uid'])): ?>
                                            <small class="text-danger"><?= $errors['card_uid'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_member">Assign to Member</label>
                                        <select class="form-control" id="id_member" name="id_member">
                                            <option value="">Select Member (Optional)</option>
                                            <?php foreach ($members as $member): ?>
                                                <option value="<?= $member['id_member'] ?>" <?= old('id_member') == $member['id_member'] ? 'selected' : '' ?>>
                                                    <?= $member['nama_member'] ?> (<?= $member['id_member'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_status">Card Status *</label>
                                        <select class="form-control" id="card_status" name="card_status" required>
                                            <option value="active" <?= old('card_status', 'active') == 'active' ? 'selected' : '' ?>>Active</option>
                                            <option value="inactive" <?= old('card_status') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                            <option value="blocked" <?= old('card_status') == 'blocked' ? 'selected' : '' ?>>Blocked</option>
                                            <option value="lost" <?= old('card_status') == 'lost' ? 'selected' : '' ?>>Lost</option>
                                        </select>
                                        <?php if (isset($errors['card_status'])): ?>
                                            <small class="text-danger"><?= $errors['card_status'] ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="initial_balance">Initial Balance (Rp)</label>
                                        <input type="number" class="form-control" id="initial_balance" name="initial_balance"
                                               value="<?= old('initial_balance', 0) ?>" min="0" step="1000">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="issued_date">Issued Date</label>
                                        <input type="date" class="form-control" id="issued_date" name="issued_date"
                                               value="<?= old('issued_date', date('Y-m-d')) ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expiry_date">Expiry Date</label>
                                        <input type="date" class="form-control" id="expiry_date" name="expiry_date"
                                               value="<?= old('expiry_date') ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3"
                                                  placeholder="Optional notes about the card"><?= old('notes') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Create RFID Card</button>
                                    <a href="<?= base_url('admin/rfid') ?>" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-generate UID if needed
document.getElementById('card_uid').addEventListener('input', function(e) {
    // Remove any non-alphanumeric characters and convert to uppercase
    this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
});
</script>

<?= $this->endSection() ?>
