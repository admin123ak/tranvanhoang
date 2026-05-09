<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tao Package moi</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?= site_url('user/packages/create') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="package_name">Ten package <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="package_name" name="package_name"
                                           value="<?= esc(old('package_name', '')) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="package_id">Package ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="package_id" name="package_id"
                                           value="<?= esc(old('package_id', '')) ?>" required>
                                    <small class="text-muted">VD: nocashrandi, pubg_mobile, ...</small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="description">Mo ta</label>
                                    <input type="text" class="form-control" id="description" name="description"
                                           value="<?= esc(old('description', '')) ?>"
                                           placeholder="Mo ta ngan gon ve package">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Tao package
                        </button>
                        <a href="<?= site_url('user/packages') ?>" class="btn btn-secondary">
                            Huy
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
