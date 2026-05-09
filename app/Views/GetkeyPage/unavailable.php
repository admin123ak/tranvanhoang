<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 text-center">
                <h3>Service Unavailable</h3>
                <p class="text-subtitle text-muted">GetKey service is currently unavailable</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <i class="ti ti-alert-circle fs-1 text-warning mb-3"></i>
                        <h5 class="mb-3">Service Not Available</h5>
                        <p class="text-muted">GetKey service chưa được cấu hình hoặc đang tạm ngưng.</p>
                        <a href="<?= site_url('dashboard') ?>" class="btn btn-primary mt-3">
                            <i class="ti ti-home me-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
