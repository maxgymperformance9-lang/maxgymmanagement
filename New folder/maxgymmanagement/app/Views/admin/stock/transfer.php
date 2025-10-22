<?= $this->extend('templates/admin_layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transfer Stock</h3>
                </div>
                <div class="card-body">
                    <form id="transferForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_warehouse_id">From Warehouse</label>
                                    <select class="form-control" id="from_warehouse_id" name="from_warehouse_id" required>
                                        <option value="">Select Warehouse</option>
                                        <!-- Warehouses will be loaded via AJAX -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to_warehouse_id">To Warehouse</label>
                                    <select class="form-control" id="to_warehouse_id" name="to_warehouse_id" required>
                                        <option value="">Select Warehouse</option>
                                        <!-- Warehouses will be loaded via AJAX -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_id">Product</label>
                                    <select class="form-control" id="product_id" name="product_id" required>
                                        <option value="">Select Product</option>
                                        <!-- Products will be loaded via AJAX -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Transfer Stock</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Load warehouses
    $.get('/admin/stock/get-warehouses', function(data) {
        if (data.success) {
            const warehouseOptions = '<option value="">Select Warehouse</option>' +
                data.data.map(warehouse =>
                    `<option value="${warehouse.id_warehouse}">${warehouse.nama_gudang}</option>`
                ).join('');
            $('#from_warehouse_id').html(warehouseOptions);
            $('#to_warehouse_id').html(warehouseOptions);
        }
    });

    // Load products
    $.get('/admin/stock/get-products', function(data) {
        if (data.success) {
            const productOptions = '<option value="">Select Product</option>' +
                data.data.map(product =>
                    `<option value="${product.id_product}">${product.nama_produk}</option>`
                ).join('');
            $('#product_id').html(productOptions);
        }
    });

    // Handle form submission
    $('#transferForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            from_warehouse_id: $('#from_warehouse_id').val(),
            to_warehouse_id: $('#to_warehouse_id').val(),
            product_id: $('#product_id').val(),
            quantity: $('#quantity').val(),
            notes: $('#notes').val()
        };

        $.post('/admin/stock/transfer', formData, function(response) {
            if (response.success) {
                alert('Stock transferred successfully!');
                $('#transferForm')[0].reset();
            } else {
                alert('Error: ' + response.message);
            }
        }).fail(function() {
            alert('An error occurred while transferring stock.');
        });
    });
});
</script>
<?= $this->endSection() ?>
