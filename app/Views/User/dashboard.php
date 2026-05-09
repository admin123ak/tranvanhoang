<?= $this->extend('Layout/Master') ?>

<?= $this->section('styles') ?>
<style>
.stat-card {
    border: none;
    border-radius: 12px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
}
.quick-action-btn {
    border: 2px dashed #e5e5e5;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: transparent;
    color: #525252;
    text-decoration: none;
    display: block;
}
.quick-action-btn:hover {
    border-color: #e66239;
    color: #e66239;
    background: #fef5f2;
}
.quick-action-btn i {
    font-size: 28px;
    margin-bottom: 8px;
    display: block;
}
.device-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f5f5f5;
}
.device-item:last-child {
    border-bottom: none;
}
.device-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #fff;
}
.contact-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
    text-decoration: none;
    color: #262626;
}
.contact-link:hover {
    color: #e66239;
}
.contact-link i {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    background: #f5f5f5;
    color: #525252;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
                <h1 class="fs-3 mb-1">Xin chào, <?= getName($user) ?> 👋</h1>
                <p class="text-muted mb-0">Đây là tổng quan tài khoản của bạn</p>
            </div>
            <div>
                <span class="badge bg-primary rounded-pill px-3 py-2"><?= getLevel($user->level) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-3">
    <!-- Saldo -->
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary me-3">
                        <i class="ti ti-wallet"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Saldo</p>
                        <h3 class="fw-bold mb-0">₹ <?= number_format($user->saldo, 2) ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Keys -->
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success me-3">
                        <i class="ti ti-key"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Active Keys</p>
                        <h3 class="fw-bold mb-0"><?= isset($totalKeys) ? $totalKeys : 0 ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Devices -->
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-info me-3">
                        <i class="ti ti-device-mobile"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Devices</p>
                        <h3 class="fw-bold mb-0"><?= isset($totalDevices) ? $totalDevices : 0 ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expired Keys -->
    <div class="col-xl-3 col-md-6">
        <div class="card stat-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-danger me-3">
                        <i class="ti ti-clock-x"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 small text-uppercase fw-semibold">Expired</p>
                        <h3 class="fw-bold mb-0"><?= isset($totalExpired) ? $totalExpired : 0 ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <!-- Registration History -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-receipt me-2"></i>Lịch sử đăng ký
                </h5>
                <a href="<?= site_url('keys') ?>" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-eye me-1"></i>Xem tất cả
                </a>
            </div>
            <div class="card-body">
                <?php if (isset($history) && count($history) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Game</th>
                                <th>Key</th>
                                <th>Duration</th>
                                <th>Devices</th>
                                <th>Time</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $h) : ?>
                                <?php $in = explode("|", $h->info) ?>
                                <?php
                                $expiredDate = isset($in[4]) ? $in[4] : '';
                                $isExpired = false;
                                if ($expiredDate && strtotime($expiredDate) < time()) {
                                    $isExpired = true;
                                }
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?= isset($in[0]) ? $in[0] : '-' ?></td>
                                    <td><code class="small"><?= isset($in[1]) ? substr($in[1], 0, 8) . '**' : '-' ?></code></td>
                                    <td><span class="badge bg-warning"><?= isset($in[2]) ? $in[2] . 'H' : '-' ?></span></td>
                                    <td><span class="badge bg-info"><?= isset($in[3]) ? $in[3] . ' thiết bị' : '-' ?></span></td>
                                    <td class="text-muted small"><?= $time::parse($h->created_at)->format('d/m/Y') ?></td>
                                    <td>
                                        <?php if ($isExpired) : ?>
                                            <span class="badge bg-danger">Hết hạn</span>
                                        <?php else : ?>
                                            <span class="badge bg-success">Đang hoạt động</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else : ?>
                <div class="text-center py-5">
                    <i class="ti ti-inbox fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Chưa có lịch sử đăng ký</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-bolt me-2"></i>Thao tác nhanh
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="<?= site_url('keys/generate') ?>" class="quick-action-btn">
                            <i class="ti ti-plus"></i>
                            <span class="small fw-semibold">Tạo Key mới</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= site_url('recharge') ?>" class="quick-action-btn">
                            <i class="ti ti-credit-card"></i>
                            <span class="small fw-semibold">Nạp tiền</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= site_url('keys') ?>" class="quick-action-btn">
                            <i class="ti ti-list-check"></i>
                            <span class="small fw-semibold">Danh sách Key</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= site_url('settings') ?>" class="quick-action-btn">
                            <i class="ti ti-settings"></i>
                            <span class="small fw-semibold">Cài đặt</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-address-book me-2"></i>Liên hệ
                </h5>
            </div>
            <div class="card-body">
                <a href="https://t.me/SpecialModj" target="_blank" class="contact-link">
                    <i class="ti ti-brand-telegram" style="background:#e5f5ff;color:#0088cc;"></i>
                    <div>
                        <div class="fw-semibold small">Telegram</div>
                        <div class="text-muted small">@SpecialModj</div>
                    </div>
                </a>
                <a href="<?= site_url('api-docs') ?>" class="contact-link">
                    <i class="ti ti-file-code" style="background:#fff5e5;color:#e66239;"></i>
                    <div>
                        <div class="fw-semibold small">API Docs</div>
                        <div class="text-muted small">Tài liệu tích hợp</div>
                    </div>
                </a>
                <a href="<?= site_url('settings') ?>" class="contact-link">
                    <i class="ti ti-lifebuoy" style="background:#e5ffe5;color:#00c951;"></i>
                    <div>
                        <div class="fw-semibold small">Hỗ trợ</div>
                        <div class="text-muted small">Trung tâm trợ giúp</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Account Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-user me-2"></i>Tài khoản
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-primary me-3" style="width:42px;height:42px;font-size:20px;">
                        <i class="ti ti-user"></i>
                    </div>
                    <div>
                        <div class="fw-semibold"><?= getName($user) ?></div>
                        <div class="text-muted small">@<?= $user->username ?></div>
                    </div>
                </div>
                <div class="small">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Level</span>
                        <span class="fw-semibold"><?= $user->level == 1 ? 'Admin' : 'User' ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Lần cuối đăng nhập</span>
                        <span class="fw-semibold"><?= $time::parse(session()->time_since)->humanize() ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Thời gian online</span>
                        <span class="fw-semibold"><?= $time::now()->difference($time::parse(session()->time_login))->humanize() ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
