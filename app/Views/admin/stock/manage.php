<?= $this->extend('templates/admin_layout') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Kelola Stok</h4>
                        <p class="card-category">Set minimum dan maximum stock per produk per gudang</p>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <select id="warehouse-select" class="form-control">
                                    <option value="">Pilih Gudang</option>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse['id_warehouse'] ?>"><?= $warehouse['nama_gudang'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <a href="<?= base_url('admin/stock') ?>" class="btn btn-secondary pull-right">
                                    <i class="material-icons">arrow_back</i> Kembali
                                </a>
                            </div>
                        </div>

                        <div id="stock-management-content" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-hover" id="manageStockTable">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>Produk</th>
                                            <th>Stok Saat Ini</th>
                                            <th>Min Stock</th>
                                            <th>Max Stock</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="manageStockTableBody">
                                        <!-- Data will be loaded via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="no-warehouse-selected" class="text-center">
                            <p class="text-muted">Pilih gudang untuk mengelola stok</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for editing stock levels -->
<div class="modal fade" id="editStockModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Level Stok</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editStockForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_warehouse_id" name="warehouse_id">
                    <input type="hidden" id="edit_product_id" name="product_id">

                    <div class="form-group">
                        <label>Produk</label>
                        <p class="form-control-plaintext" id="edit_product_name"></p>
                    </div>

                    <div class="form-group">
                        <label for="edit_min_stock">Minimum Stock</label>
                        <input type="number" class="form-control" id="edit_min_stock" name="min_stock" min="0">
                    </div>

                    <div class="form-group">
                        <label for="edit_max_stock">Maximum Stock</label>
                        <input type="number" class="form-control" id="edit_max_stock" name="max_stock" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#warehouse-select').change(function() {
        const warehouseId = $(this).val();
        if (warehouseId) {
            loadWarehouseStock(warehouseId);
            $('#stock-management-content').show();
            $('#no-warehouse-selected').hide();
        } else {
            $('#stock-management-content').hide();
            $('#no-warehouse-selected').show();
        }
    });

    function loadWarehouseStock(warehouseId) {
        $.ajax({
            url: '<?= base_url('admin/stock/by-warehouse') ?>',
            type: 'POST',
            data: { id_warehouse: warehouseId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayStockData(response.data, warehouseId);
                } else {
                    $('#manageStockTableBody').html(`
                        <tr>
                            <td colspan="6" class="text-center text-danger">Gagal memuat data stok</td>
                        </tr>
                    `);
                }
            },
            error: function() {
                $('#manageStockTableBody').html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger">Terjadi kesalahan</td>
                    </tr>
                `);
            }
        });
    }

    function displayStockData(stocks, warehouseId) {
        if (stocks.length > 0) {
            let html = '';
            stocks.forEach(function(stock) {
                let statusBadge = '<span class="badge badge-success">Normal</span>';
                if (stock.quantity <= stock.min_stock) {
                    statusBadge = '<span class="badge badge-danger">Stok Rendah</span>';
                } else if (stock.max_stock && stock.quantity >= stock.max_stock) {
                    statusBadge = '<span class="badge badge-warning">Stok Penuh</span>';
                }

                html += `
                    <tr>
                        <td>${stock.nama_produk}</td>
                        <td>${stock.quantity}</td>
                        <td>${stock.min_stock}</td>
                        <td>${stock.max_stock || '-'}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-stock"
                                    data-warehouse="${warehouseId}"
                                    data-product="${stock.id_product}"
                                    data-product-name="${stock.nama_produk}"
                                    data-min="${stock.min_stock}"
                                    data-max="${stock.max_stock || ''}">
                                <i class="material-icons">edit</i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#manageStockTableBody').html(html);
        } else {
            $('#manageStockTableBody').html(`
                <tr>
                    <td colspan="6" class="text-center">Tidak ada produk di gudang ini</td>
                </tr>
            `);
        }
    }

    // Edit stock modal
    $(document).on('click', '.edit-stock', function() {
        const warehouseId = $(this).data('warehouse');
        const productId = $(this).data('product');
        const productName = $(this).data('product-name');
        const minStock = $(this).data('min');
        const maxStock = $(this).data('max');

        $('#edit_warehouse_id').val(warehouseId);
        $('#edit_product_id').val(productId);
        $('#edit_product_name').text(productName);
        $('#edit_min_stock').val(minStock);
        $('#edit_max_stock').val(maxStock);

        $('#editStockModal').modal('show');
    });

    // Save stock changes
    $('#editStockForm').submit(function(e) {
        e.preventDefault();

        const formData = {
            warehouse_id: $('#edit_warehouse_id').val(),
            product_id: $('#edit_product_id').val(),
            min_stock: $('#edit_min_stock').val(),
            max_stock: $('#edit_max_stock').val()
        };

        $.ajax({
            url: '<?= base_url('admin/stock/update-levels') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editStockModal').modal('hide');
                    loadWarehouseStock(formData.warehouse_id);
                    alert('Level stok berhasil diupdate');
                } else {
                    alert('Gagal mengupdate level stok: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat mengupdate level stok');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
