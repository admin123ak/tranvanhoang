<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Package</h3>
                <p class="text-subtitle text-muted">Update package information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/packages') ?>">Packages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <?php if (session()->getFlashdata('msgDanger')) : ?>
                    <div class="alert alert-danger alert-dismissible show fade">
                        <?= session()->getFlashdata('msgDanger') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Package Information</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>

                        <div class="form-group mb-3">
                            <label for="package_name" class="form-label">Package Name <span class="text-danger">*</span></label>
                            <input type="text" name="package_name" id="package_name" class="form-control" placeholder="e.g. PUBG Mobile" value="<?= old('package_name') ?: $package->package_name ?>" required>
                            <?php if ($validation->hasError('package_name')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('package_name') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="package_id" class="form-label">Package ID <span class="text-danger">*</span></label>
                            <input type="text" name="package_id" id="package_id" class="form-control" placeholder="e.g. com.tencent.ig" value="<?= old('package_id') ?: $package->package_id ?>" required>
                            <small class="text-muted">Android package name (e.g. com.tencent.ig for PUBG Mobile)</small>
                            <?php if ($validation->hasError('package_id')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('package_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Optional description"><?= old('description') ?: $package->description ?></textarea>
                            <?php if ($validation->hasError('description')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('description') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="1" <?= (old('status') ?: $package->status) == '1' ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= (old('status') ?: $package->status) == '0' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <?php if ($validation->hasError('status')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('status') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Package</button>
                            <a href="<?= site_url('admin/packages') ?>" class="btn btn-secondary">Cancel</a>
                        </div>

                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
