<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>GetKey Configuration</h3>
                <p class="text-subtitle text-muted">Cấu hình hệ thống GetKey tự động</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">GetKey Config</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <?php if (session()->getFlashdata('msgSuccess')) : ?>
            <div class="alert alert-success alert-dismissible show fade">
                <?= session()->getFlashdata('msgSuccess') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('msgDanger')) : ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <?= session()->getFlashdata('msgDanger') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="ti ti-settings"></i> Cấu hình GetKey
                        </h4>
                    </div>
                    <div class="card-body">
                        <?= form_open('admin/getkey-config/save') ?>

                        <div class="form-group mb-3">
                            <label for="admin_account" class="form-label">Tài khoản Admin <span class="text-danger">*</span></label>
                            <select name="admin_account" id="admin_account" class="form-select" required>
                                <option value="">-- Chọn Admin --</option>
                                <?php if (is_array($adminUsers) && count($adminUsers) > 0) : ?>
                                    <?php foreach ($adminUsers as $admin) : ?>
                                        <?php $aUser = is_object($admin) ? ($admin->username ?? '') : ($admin['username'] ?? ''); ?>
                                        <?php $aSaldo = is_object($admin) ? ($admin->saldo ?? 0) : ($admin['saldo'] ?? 0); ?>
                                        <?php $selected = ($config && $config->admin_account == $aUser) ? 'selected' : ''; ?>
                                        <option value="<?= esc($aUser) ?>" <?= $selected ?>>
                                            <?= esc($aUser) ?> (Balance: <?= number_format($aSaldo, 0) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">Admin account sẽ được dùng để tạo key tự động</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="package_id" class="form-label">Package <span class="text-danger">*</span></label>
                            <select name="package_id" id="package_id" class="form-select" required>
                                <option value="">-- Chọn Package --</option>
                                <?php if (is_array($packages) && count($packages) > 0) : ?>
                                    <?php foreach ($packages as $pkg) : ?>
                                        <?php $pId = is_object($pkg) ? ($pkg->id_package ?? '') : ($pkg['id_package'] ?? ''); ?>
                                        <?php $pName = is_object($pkg) ? ($pkg->package_name ?? '') : ($pkg['package_name'] ?? ''); ?>
                                        <?php $selected = ($config && $config->package_id == $pId) ? 'selected' : ''; ?>
                                        <option value="<?= $pId ?>" <?= $selected ?>>
                                            <?= esc($pName) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="youmoney_token" class="form-label">YouMoney API Token <small class="text-muted">(tùy chọn)</small></label>
                            <input type="text" name="youmoney_token" id="youmoney_token" class="form-control"
                                   value="<?= $config ? esc($config->youmoney_token) : '' ?>" placeholder="Nhập API token YouMoney để rút gọn link">
                            <small class="text-muted">Nếu có token, link sẽ tự động rút gọn qua YeuMoney</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price_per_hour" class="form-label">Giá mỗi giờ (VND) - <strong>0 = FREE</strong></label>
                            <input type="number" name="price_per_hour" id="price_per_hour" class="form-control"
                                   value="<?= $config ? $config->price_per_hour : 0 ?>" min="0" step="100">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="max_hours" class="form-label">Số giờ <span class="text-danger">*</span></label>
                                    <input type="number" name="max_hours" id="max_hours" class="form-control"
                                           value="<?= $config ? $config->max_hours : 720 ?>" min="1" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="max_devices" class="form-label">Số thiết bị <span class="text-danger">*</span></label>
                                    <input type="number" name="max_devices" id="max_devices" class="form-control"
                                           value="<?= $config ? $config->max_devices : 1 ?>" min="1" max="20" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy"></i> Lưu cấu hình
                        </button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="ti ti-info-circle"></i> Hướng dẫn
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-light-info">
                            <h6 class="fw-bold mb-2">Cách hoạt động:</h6>
                            <ol class="mb-0 ps-3">
                                <li>Cấu hình admin account, package, giá, giờ, devices</li>
                                <li>Người dùng vào trang <code>/getkey</code></li>
                                <li>Nhấn "Lấy Link" → hệ thống tự tạo key + link riêng</li>
                                <li>Mỗi người = 1 key = 1 link độc nhất</li>
                            </ol>
                        </div>

                        <?php if ($config) : ?>
                            <div class="alert alert-light-success mt-3">
                                <h6 class="fw-bold mb-2">Public Link:</h6>
                                <a href="<?= site_url('getkey') ?>" target="_blank" class="text-success">
                                    <?= site_url('getkey') ?>
                                </a>
                                <p class="mb-0 mt-2 small">Chia sẻ link này cho người dùng</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
