<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <?php if (session()->getFlashdata('msg')) : ?>
                    <div class="pb-2 px-3">
                        <div class="alert alert-<?= session()->getFlashdata('error') == true ? 'danger' : 'success' ?>">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <?= session()->getFlashdata('msg') ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12 col-xl-12">
                        <div class="card">
                            <div class="card-header card-header-tabs card-header-success">
                                <div class="nav-tabs-navigation">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-5">
                                            <h4 class="card-title"><b>Membership Packages</b></h4>
                                            <p class="card-category">Manage gym membership packages</p>
                                        </div>
                                        <div class="ml-md-auto col-auto row">
                                            <div class="col-12 col-sm-auto nav nav-tabs">
                                                <div class="nav-item">
                                                    <a class="nav-link" id="tabBtn" onclick="removeHover()" href="<?= base_url('admin/membership-packages/create'); ?>">
                                                        <i class="material-icons">add</i> Add Package
                                                        <div class="ripple-container"></div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-auto nav nav-tabs">
                                                <div class="nav-item">
                                                    <a class="nav-link" id="refreshBtn" onclick="loadPackages()" href="#" data-toggle="tab">
                                                        <i class="material-icons">refresh</i> Refresh
                                                        <div class="ripple-container"></div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="packagesContent">
                                <p class="text-center mt-3">Loading membership packages...</p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadPackages();
});

function loadPackages() {
    $.ajax({
        url: '<?= base_url('admin/membership-packages/ajax-list') ?>',
        type: 'post',
        success: function(response) {
            let html = `
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="packagesTable">
                            <thead class="text-primary">
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>PT Sessions</th>
                                    <th>Features</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>`;

            if (response.length > 0) {
                response.forEach(function(package) {
                    const statusBadge = package.status === 'aktif'
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';

                    const features = [];
                    if (package.unlimited_classes) features.push('<span class="badge badge-info">Unlimited Classes</span>');
                    if (package.locker_access) features.push('<span class="badge badge-secondary">Locker</span>');

                    html += `
                        <tr>
                            <td>${package.nama_package}</td>
                            <td>Rp ${parseInt(package.harga).toLocaleString()}</td>
                            <td>${package.durasi_hari} days</td>
                            <td>${package.pt_sessions || 0}</td>
                            <td>${features.join(' ')}</td>
                            <td>${statusBadge}</td>
                            <td>
                                <a href="<?= base_url('admin/membership-packages/edit/') ?>${package.id_package}" class="btn btn-sm btn-primary">
                                    <i class="material-icons">edit</i>
                                </a>
                                <button class="btn btn-sm btn-warning toggle-status" data-id="${package.id_package}" data-status="${package.status}">
                                    <i class="material-icons">${package.status === 'aktif' ? 'block' : 'check'}</i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-package" data-id="${package.id_package}">
                                    <i class="material-icons">delete</i>
                                </button>
                            </td>
                        </tr>`;
                });
            } else {
                html += `
                    <tr>
                        <td colspan="7" class="text-center">No membership packages found. <a href="<?= base_url('admin/membership-packages/create') ?>">Create one now</a></td>
                    </tr>`;
            }

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>`;

            $('#packagesContent').html(html);
            $('#refreshBtn').removeClass('active show');
        },
        error: function(xhr, status, thrown) {
            console.log(thrown);
            $('#packagesContent').html('<p class="text-danger text-center">Failed to load membership packages.</p>');
        }
    });
}

// Toggle status
$(document).on('click', '.toggle-status', function() {
    const id = $(this).data('id');
    const currentStatus = $(this).data('status');

    if (confirm('Are you sure you want to ' + (currentStatus === 'aktif' ? 'deactivate' : 'activate') + ' this package?')) {
        $.ajax({
            url: '<?= base_url('admin/membership-packages/toggle-status/') ?>' + id,
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadPackages();
                }
            }
        });
    }
});

// Delete package
$(document).on('click', '.delete-package', function() {
    const id = $(this).data('id');

    if (confirm('Are you sure you want to delete this package?')) {
        $.ajax({
            url: '<?= base_url('admin/membership-packages/delete/') ?>' + id,
            method: 'DELETE',
            success: function() {
                loadPackages();
            }
        });
    }
});

// Hilangkan hover saat klik tab tambah data
function removeHover() {
    setTimeout(() => {
        $('#tabBtn').removeClass('active show');
    }, 250);
}
</script>
<?= $this->endSection() ?>
