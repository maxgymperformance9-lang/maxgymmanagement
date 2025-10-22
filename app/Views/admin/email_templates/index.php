<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Email Templates</h4>
                        <p class="card-category">Manage and preview email templates</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header card-header-success">
                                        <h4 class="card-title">Welcome Email</h4>
                                    </div>
                                    <div class="card-body">
                                        <p>Sent when a new member registers</p>
                                        <a href="<?= base_url('admin/email-templates/preview/welcome') ?>" class="btn btn-success btn-sm">
                                            <i class="material-icons">visibility</i> Preview
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header card-header-warning">
                                        <h4 class="card-title">Expiration Reminder</h4>
                                    </div>
                                    <div class="card-body">
                                        <p>Sent when membership is about to expire</p>
                                        <a href="<?= base_url('admin/email-templates/preview/expiration_reminder') ?>" class="btn btn-warning btn-sm">
                                            <i class="material-icons">visibility</i> Preview
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header card-header-danger">
                                        <h4 class="card-title">Membership Expired</h4>
                                    </div>
                                    <div class="card-body">
                                        <p>Sent when membership has expired</p>
                                        <a href="<?= base_url('admin/email-templates/preview/membership_expired') ?>" class="btn btn-danger btn-sm">
                                            <i class="material-icons">visibility</i> Preview
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header card-header-info">
                                        <h4 class="card-title">Renewal Confirmation</h4>
                                    </div>
                                    <div class="card-body">
                                        <p>Sent when membership is renewed</p>
                                        <a href="<?= base_url('admin/email-templates/preview/renewal_confirmation') ?>" class="btn btn-info btn-sm">
                                            <i class="material-icons">visibility</i> Preview
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header card-header-primary">
                                        <h4 class="card-title">Password Reset</h4>
                                    </div>
                                    <div class="card-body">
                                        <p>Sent for password reset requests</p>
                                        <a href="<?= base_url('admin/email-templates/preview/password_reset') ?>" class="btn btn-primary btn-sm">
                                            <i class="material-icons">visibility</i> Preview
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
