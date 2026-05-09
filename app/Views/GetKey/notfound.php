<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 text-center">
                <h3>Key Not Found</h3>
                <p class="text-subtitle text-muted">The key you're looking for doesn't exist</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <i class="ti ti-key-off fs-1 text-danger mb-3"></i>
                        <h5 class="mb-3">Key Not Found</h5>
                        <p class="text-muted">Link này không tồn tại hoặc đã bị xóa.</p>
                        <a href="<?= site_url('getkey') ?>" class="btn btn-primary mt-3">
                            <i class="ti ti-arrow-left me-1"></i> Get New Key
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
