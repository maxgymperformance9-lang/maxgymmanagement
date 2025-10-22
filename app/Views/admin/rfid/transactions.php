<?= $this->extend('templates/admin_layout') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">
                            <?php if (isset($rfidCard)): ?>
                                Transactions for Card: <code><?= $rfidCard['rfid_uid'] ?></code>
                            <?php else: ?>
                                All RFID Transactions
                            <?php endif; ?>
                        </h4>
                        <p class="card-category">View and manage RFID card transactions</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="<?= base_url('admin/rfid') ?>" class="btn btn-secondary">
                                    <i class="material-icons">arrow_back</i> Back to RFID Cards
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Card UID</th>
                                        <th>Transaction Type</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th>Processed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($transactions)): ?>
                                        <?php foreach ($transactions as $transaction): ?>
                                            <tr>
                                                <td><?= $transaction['id_transaction'] ?></td>
                                                <td><code><?= $transaction['card_uid'] ?? 'N/A' ?></code></td>
                                                <td>
                                                    <span class="badge badge-<?= $transaction['transaction_type'] == 'topup' ? 'success' : ($transaction['transaction_type'] == 'payment' ? 'warning' : 'info') ?>">
                                                        <?= ucfirst($transaction['transaction_type']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-<?= $transaction['transaction_type'] == 'topup' ? 'success' : 'danger' ?>">
                                                        <?= $transaction['transaction_type'] == 'topup' ? '+' : '-' ?>
                                                        Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                                                    </span>
                                                </td>
                                                <td><?= $transaction['description'] ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($transaction['transaction_date'])) ?></td>
                                                <td>
                                                    <?php if ($transaction['processed_by']): ?>
                                                        Staff ID: <?= $transaction['processed_by'] ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">System</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <p class="text-muted">No transactions found</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
