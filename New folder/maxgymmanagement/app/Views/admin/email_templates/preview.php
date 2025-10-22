<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Email Template Preview</h4>
                        <p class="card-category">Preview of <?= ucwords(str_replace('_', ' ', $template)) ?> Template</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div style="border: 1px solid #ddd; padding: 20px; background: #f9f9f9;">
                                    <?= view('email/' . $template_file, [
                                        'member' => $member,
                                        'days_left' => $days_left ?? null,
                                        'reset_link' => $reset_link ?? null
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <a href="<?= base_url('admin/email-templates') ?>" class="btn btn-secondary">
                                    <i class="material-icons">arrow_back</i> Back to Templates
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
