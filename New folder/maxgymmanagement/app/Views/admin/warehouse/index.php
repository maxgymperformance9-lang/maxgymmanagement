<?= $this->extend('templates/admin_layout') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Data Gudang</h4>
                        <p class="card-category">Kelola data gudang untuk manajemen stok</p>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <a href="<?= base_url('admin/warehouse/create') ?>" class="btn btn-primary">
                                    <i class="material-icons">add</i> Tambah Gudang
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" id="warehouseTable">
                                <thead class="text-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Gudang</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                        <th>Jumlah Produk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="warehouseTableBody">
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
    loadWarehouses();

    function loadWarehouses() {
        $.ajax({
            url: '<?= base_url('admin/warehouse/get-warehouses') ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.data && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(function(warehouse, index) {
                        const statusBadge = warehouse.status === 'active'
                            ? '<span class="badge badge-success">Aktif</span>'
                            : '<span class="badge badge-danger">Tidak Aktif</span>';

                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${warehouse.nama_gudang}</td>
                                <td>${warehouse.lokasi || '-'}</td>
                                <td>${statusBadge}</td>
                                <td>${warehouse.total_products || 0}</td>
                                <td>
                                    <a href="<?= base_url('admin/warehouse/edit/') ?>${warehouse.id_warehouse}"
                                       class="btn btn-sm btn-warning">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <button class="btn btn-sm btn-danger delete-warehouse"
                                            data-id="${warehouse.id_warehouse}"
                                            data-name="${warehouse.nama_gudang}">
                                        <i class="material-icons">delete</i>
                                    </button>
                                    <button class="btn btn-sm ${warehouse.status === 'active' ? 'btn-secondary' : 'btn-success'} toggle-status"
                                            data-id="${warehouse.id_warehouse}"
                                            data-status="${warehouse.status}">
                                        <i class="material-icons">${warehouse.status === 'active' ? 'block' : 'check'}</i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#warehouseTableBody').html(html);
                } else {
                    $('#warehouseTableBody').html(`
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data gudang</td>
                        </tr>
                    `);
                }
            },
            error: function() {
                $('#warehouseTableBody').html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger">Gagal memuat data</td>
                    </tr>
                `);
            }
        });
    }

    // Delete warehouse
    $(document).on('click', '.delete-warehouse', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        if (confirm(`Apakah Anda yakin ingin menghapus gudang "${name}"?`)) {
            $.ajax({
                url: `<?= base_url('admin/warehouse/delete/') ?>${id}`,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Gudang berhasil dihapus');
                        loadWarehouses();
                    } else {
                        alert('Gagal menghapus gudang: ' + response.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghapus gudang');
                }
            });
        }
    });

    // Toggle status
    $(document).on('click', '.toggle-status', function() {
        const id = $(this).data('id');
        const currentStatus = $(this).data('status');
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        const action = newStatus === 'active' ? 'mengaktifkan' : 'menonaktifkan';

        if (confirm(`Apakah Anda yakin ingin ${action} gudang ini?`)) {
            $.ajax({
                url: `<?= base_url('admin/warehouse/toggle-status/') ?>${id}`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(`Gudang berhasil ${action}`);
                        loadWarehouses();
                    } else {
                        alert(`Gagal ${action} gudang: ` + response.message);
                    }
                },
                error: function() {
                    alert(`Terjadi kesalahan saat ${action} gudang`);
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>
