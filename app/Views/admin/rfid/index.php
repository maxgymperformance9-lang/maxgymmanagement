<?= $this->extend('templates/admin_layout') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">RFID Cards Management</h4>
                        <p class="card-category">Manage RFID cards, balances, and transactions</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="<?= base_url('admin/rfid/create') ?>" class="btn btn-primary">
                                    <i class="material-icons">add</i> Add New RFID Card
                                </a>
                                <a href="<?= base_url('admin/rfid/transactions') ?>" class="btn btn-info">
                                    <i class="material-icons">receipt</i> View All Transactions
                                </a>
                            </div>
                        </div>

                        <?php if (session()->getFlashdata('success')) : ?>
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="material-icons">close</i>
                                </button>
                                <span><?= session()->getFlashdata('success') ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')) : ?>
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="material-icons">close</i>
                                </button>
                                <span><?= session()->getFlashdata('error') ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Card UID</th>
                                        <th>Member</th>
                                        <th>Status</th>
                                        <th>Balance</th>
                                        <th>Issued Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rfidCards as $card) : ?>
                                        <tr>
                                            <td><?= $card['id_rfid'] ?></td>
                                            <td><code><?= $card['rfid_uid'] ?></code></td>
                                            <td>
                                                <?php if ($card['id_member']) : ?>
                                                    <?php
                                                    $member = array_filter($members, function($m) use ($card) {
                                                        return $m['id_member'] == $card['id_member'];
                                                    });
                                                    $member = reset($member);
                                                    echo $member ? $member['nama_member'] : 'Unknown';
                                                    ?>
                                                <?php else : ?>
                                                    <span class="text-muted">Unassigned</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= $card['card_status'] == 'active' ? 'success' : ($card['card_status'] == 'inactive' ? 'warning' : 'danger') ?>">
                                                    <?= ucfirst($card['card_status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                // Get balance for this card
                                                $balance = 0;
                                                if (isset($card['balance'])) {
                                                    $balance = $card['balance'];
                                                }
                                                echo 'Rp ' . number_format($balance, 0, ',', '.');
                                                ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($card['issued_date'])) ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/rfid/edit/' . $card['id_rfid']) ?>" class="btn btn-sm btn-warning">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-info" onclick="topupModal(<?= $card['id_rfid'] ?>)">
                                                    <i class="material-icons">add_circle</i>
                                                </button>
                                                <a href="<?= base_url('admin/rfid/transactions/' . $card['id_rfid']) ?>" class="btn btn-sm btn-secondary">
                                                    <i class="material-icons">receipt</i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteCard(<?= $card['id_rfid'] ?>)">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Topup Modal -->
<div class="modal fade" id="topupModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Topup Balance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="topupForm" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="topup_amount">Amount (Rp)</label>
                        <input type="number" class="form-control" id="topup_amount" name="amount" required min="1000">
                    </div>
                    <div class="form-group">
                        <label for="topup_description">Description</label>
                        <input type="text" class="form-control" id="topup_description" name="description" placeholder="Optional">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Topup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function topupModal(cardId) {
    $('#topupForm').attr('action', '<?= base_url('admin/rfid/topup/') ?>' + cardId);
    $('#topupModal').modal('show');
}

function deleteCard(cardId) {
    if (confirm('Are you sure you want to delete this RFID card? This action cannot be undone.')) {
        // Create a form and submit it
        var form = document.createElement('form');
        form.method = 'post';
        form.action = '<?= base_url('admin/rfid/delete/') ?>' + cardId;

        // Add CSRF token if needed
        var csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'csrf_token';
            input.value = csrfToken.getAttribute('content');
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?= $this->endSection() ?>
