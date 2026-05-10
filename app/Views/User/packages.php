<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Package cua toi</h3>
                <p class="text-subtitle text-muted">Quan ly package va tao package moi</p>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('msgSuccess')) : ?>
        <div class="alert alert-success alert-dismissible show fade">
            <?= session()->getFlashdata('msgSuccess') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('msgDanger')) : ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <?= session()->getFlashdata('msgDanger') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('msgWarning')) : ?>
        <div class="alert alert-warning alert-dismissible show fade">
            <?= session()->getFlashdata('msgWarning') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-3">
        <div class="col-12">
            <?php if ($canCreate): ?>
                <a href="<?= site_url('user/packages/create') ?>" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Tao package moi
                </a>
            <?php else: ?>
                <a href="<?= site_url('plans') ?>" class="btn btn-warning">
                    <i class="ti ti-shopping-cart me-1"></i> Mua goi de tao package
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Plan Status Card for non-admin -->
    <?php if (!$isAdmin && $planStats && !empty($planStats['plan_name'])): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="ti ti-crown me-2"></i> <?= esc($planStats['plan_name']) ?></h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h4><?= (int)($planStats['max_packages'] ?? 0) ?>/<?= (int)($planStats['max_packages'] ?? 0) ?></h4>
                            <p class="text-muted mb-0">Package quota</p>
                        </div>
                        <div class="col-md-4">
                            <h4><?= (int)($planStats['max_keys'] ?? 0) ?>/<?= (int)($planStats['max_keys'] ?? 0) ?></h4>
                            <p class="text-muted mb-0">Key quota</p>
                        </div>
                        <div class="col-md-4">
                            <h4><?= date('d/m/Y', strtotime($planStats['expires_at'] ?? date('Y-m-d'))) ?></h4>
                            <p class="text-muted mb-0">Het han</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Danh sach package</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <small><i class="ti ti-info-circle"></i> Package thuộc gói của bạn. Tạo package tại trang <a href="<?= site_url('keys/generate') ?>">Tạo Keys</a> để sử dụng.</small>
                    </div>
                    <?php if (empty($packages)): ?>
                        <p class="text-muted text-center">Chua co package nao</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ten</th>
                                        <th>Package ID</th>
                                        <th>Mo ta</th>
                                        <th>Trang thai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($packages as $pkg): ?>
                                        <tr>
                                            <td><?= esc($pkg->id_package ?? $pkg->id ?? '') ?></td>
                                            <td><strong><?= esc($pkg->package_name) ?></strong></td>
                                            <td><code><?= esc($pkg->package_id) ?></code></td>
                                            <td><?= esc($pkg->description ?? '-') ?></td>
                                            <td>
                                                <?php if (($pkg->status ?? 1) == 1): ?>
                                                    <span class="badge bg-success">Hoat dong</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Tat</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
