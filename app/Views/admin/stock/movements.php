<?= $this->extend('templates/admin_layout') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Riwayat Pergerakan Stok</h4>
                        <p class="card-category">Tracking semua pergerakan stok masuk, keluar, dan transfer</p>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <a href="<?= base_url('admin/stock') ?>" class="btn btn-secondary">
                                    <i class="material-icons">arrow_back</i> Kembali ke Stok
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="movementsTable">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Gudang</th>
                                        <th>Produk</th>
                                        <th>Tipe</th>
                                        <th>Quantity</th>
                                        <th>Referensi</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody id="movementsTableBody">
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadMovements();

    function loadMovements() {
        $.ajax({
            url: '<?= base_url('admin/stock/movements') ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.data && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(function(movement) {
                        let typeBadge = '';
                        switch (movement.movement_type) {
                            case 'in':
                                typeBadge = '<span class="badge badge-success">Masuk</span>';
                                break;
                            case 'out':
                                typeBadge = '<span class="badge badge-warning">Keluar</span>';
                                break;
                            case 'transfer':
                                typeBadge = '<span class="badge badge-info">Transfer</span>';
                                break;
                            case 'adjustment':
                                typeBadge = '<span class="badge badge-secondary">Penyesuaian</span>';
                                break;
                        }

                        const transferInfo = movement.movement_type === 'transfer'
                            ? `<br><small>Asal: ${movement.from_warehouse_name || '-'}<br>Tujuan: ${movement.to_warehouse_name || '-'}</small>`
                            : '';

                        html += `
                            <tr>
                                <td>${new Date(movement.created_at).toLocaleString('id-ID')}</td>
                                <td>${movement.nama_gudang}${transferInfo}</td>
                                <td>${movement.nama_produk}</td>
                                <td>${typeBadge}</td>
                                <td>${movement.movement_type === 'out' || movement.movement_type === 'transfer' ? '-' : '+'}${movement.quantity}</td>
                                <td>${movement.reference || '-'}</td>
                                <td>${movement.notes || '-'}</td>
                            </tr>
                        `;
                    });
                    $('#movementsTableBody').html(html);
                } else {
                    $('#movementsTableBody').html(`
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pergerakan stok</td>
                        </tr>
                    `);
                }
            },
            error: function() {
                $('#movementsTableBody').html(`
                    <tr>
                        <td colspan="7" class="text-center text-danger">Gagal memuat data</td>
                    </tr>
                `);
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
