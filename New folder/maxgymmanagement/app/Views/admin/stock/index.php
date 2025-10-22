<?= $this->extend('templates/admin_layout') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Stok Gudang</h4>
                        <p class="card-category">Kelola stok produk di setiap gudang</p>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('admin/stock/in') ?>" class="btn btn-success">
                                        <i class="material-icons">add_circle</i> Barang Masuk
                                    </a>
                                    <a href="<?= base_url('admin/stock/out') ?>" class="btn btn-warning">
                                        <i class="material-icons">remove_circle</i> Barang Keluar
                                    </a>
                                    <a href="<?= base_url('admin/stock/transfer') ?>" class="btn btn-info">
                                        <i class="material-icons">swap_horiz</i> Transfer Stok
                                    </a>
                                    <a href="<?= base_url('admin/stock/movements') ?>" class="btn btn-secondary">
                                        <i class="material-icons">history</i> Riwayat Pergerakan
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="stockTable">
                                <thead class="text-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Gudang</th>
                                        <th>Produk</th>
                                        <th>Stok</th>
                                        <th>Min Stok</th>
                                        <th>Max Stok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="stockTableBody">
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
    loadStocks();

    function loadStocks() {
        $.ajax({
            url: '<?= base_url('admin/stock') ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.data && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(function(stock, index) {
                        let statusBadge = '<span class="badge badge-success">Normal</span>';
                        if (stock.quantity <= stock.min_stock) {
                            statusBadge = '<span class="badge badge-danger">Stok Rendah</span>';
                        } else if (stock.max_stock && stock.quantity >= stock.max_stock) {
                            statusBadge = '<span class="badge badge-warning">Stok Penuh</span>';
                        }

                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${stock.nama_gudang}</td>
                                <td>${stock.nama_produk}</td>
                                <td>${stock.quantity}</td>
                                <td>${stock.min_stock}</td>
                                <td>${stock.max_stock || '-'}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <button class="btn btn-sm btn-info view-movements"
                                            data-warehouse="${stock.id_warehouse}"
                                            data-product="${stock.id_product}">
                                        <i class="material-icons">history</i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#stockTableBody').html(html);
                } else {
                    $('#stockTableBody').html(`
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data stok</td>
                        </tr>
                    `);
                }
            },
            error: function() {
                $('#stockTableBody').html(`
                    <tr>
                        <td colspan="8" class="text-center text-danger">Gagal memuat data</td>
                    </tr>
                `);
            }
        });
    }

    // View movements for specific stock
    $(document).on('click', '.view-movements', function() {
        const warehouseId = $(this).data('warehouse');
        const productId = $(this).data('product');
        // Redirect to movements page with filters
        window.location.href = `<?= base_url('admin/stock/movements') ?>?warehouse=${warehouseId}&product=${productId}`;
    });
});
</script>
<?= $this->endSection() ?>
