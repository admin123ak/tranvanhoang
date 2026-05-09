<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<?php
    $step = service('request')->getGet('step');
    $isVerified = ($step === 'done');
    $yeuMoneyUrl = $link->short_url ?: null;
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 text-center">
                <h3>Get Key - <?= esc($link->name) ?></h3>
                <p class="text-subtitle text-muted">Hoàn thành bước bên dưới để nhận key</p>
            </div>
        </div>
    </div>

    <section class="section">
        <?php if (session()->getFlashdata('msgDanger')) : ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <?= session()->getFlashdata('msgDanger') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Package Info Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center p-4">
                        <div class="getkey-icon mb-3">
                            <i class="ti ti-package"></i>
                        </div>
                        <h5 class="fw-bold mb-1"><?= esc($link->package_name ?? 'Package') ?></h5>
                        <p class="text-muted small mb-3"><?= esc($link->pkg_code ?? '') ?></p>

                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <div class="stat-box">
                                    <span class="d-block fw-bold text-primary"><?= $link->max_hours ?>h</span>
                                    <small class="text-muted">Thời hạn</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-box">
                                    <span class="d-block fw-bold text-success"><?= $link->max_devices ?></span>
                                    <small class="text-muted">Thiết bị</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-box">
                                    <span class="d-block fw-bold text-info"><?= $link->total_keys_created ?></span>
                                    <small class="text-muted">Đã tạo</small>
                                </div>
                            </div>
                        </div>

                        <?php if ($link->price_per_hour > 0) : ?>
                            <div class="alert alert-light-info mb-0">
                                <strong>Giá:</strong> <?= number_format($link->price_per_hour, 0) ?> VND/giờ
                            </div>
                        <?php else : ?>
                            <div class="alert alert-light-success mb-0">
                                <i class="ti ti-gift me-1"></i> <strong>MIỄN PHÍ</strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Step Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header py-3">
                        <h5 class="mb-0">
                            <i class="ti ti-steps me-2"></i> Các bước nhận Key
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Step 1: Go through YeuMoney link -->
                        <div class="step-item mb-3 <?= $yeuMoneyUrl ? '' : 'd-none' ?>">
                            <div class="d-flex align-items-center gap-3">
                                <div class="step-number">1</div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Xem quảng cáo / Hoàn thành bước</h6>
                                    <p class="text-muted small mb-2">Nhấn nút bên dưới để đi qua link YeuMoney</p>
                                    <a href="<?= esc($yeuMoneyUrl) ?>?callback=<?= urlencode(site_url('get/' . $link->slug . '?step=done')) ?>"
                                       class="btn btn-getkey" target="_blank" id="btnStep1">
                                        <i class="ti ti-external-link me-1"></i> Đi đến Link
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Loading after returning -->
                        <div id="step-loading" class="step-item mb-3 d-none">
                            <div class="d-flex align-items-center gap-3">
                                <div class="spinner-border text-primary" role="status"></div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Đang tạo key...</h6>
                                    <p class="text-muted small mb-0">Vui lòng chờ</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Get key -->
                        <div id="step-getkey" class="step-item mb-3 <?= $isVerified ? '' : 'd-none' ?>">
                            <div class="d-flex align-items-center gap-3">
                                <div class="step-number step-success">2</div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Nhận Key</h6>
                                    <p class="text-muted small mb-2">Nhấn nút bên dưới để nhận key của bạn</p>
                                    <form action="<?= site_url('getkey/create/' . $link->slug) ?>" method="POST" id="getKeyForm">
                                        <?= csrf_field() ?>
                                    </form>
                                    <button class="btn btn-success" onclick="submitKeyForm()">
                                        <i class="ti ti-key me-1"></i> Lấy Key Ngay
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- If no YeuMoney, direct get key -->
                        <?php if (!$yeuMoneyUrl) : ?>
                            <div class="text-center">
                                <p class="text-muted mb-3">Nhấn nút bên dưới để nhận key ngay</p>
                                <form action="<?= site_url('getkey/create/' . $link->slug) ?>" method="POST" id="getKeyForm">
                                    <?= csrf_field() ?>
                                </form>
                                <button class="btn btn-getkey btn-lg" onclick="submitKeyForm()">
                                    <i class="ti ti-bolt me-1"></i> Lấy Key
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.getkey-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e66239 0%, #ff8a65 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.getkey-icon i {
    font-size: 32px;
    color: #fff;
}
.stat-box {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 10px 5px;
}
.btn-getkey {
    background: linear-gradient(135deg, #e66239 0%, #ff8a65 100%);
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    transition: all 0.3s;
}
.btn-getkey:hover {
    background: linear-gradient(135deg, #d5532a 0%, #e6744c 100%);
    transform: scale(1.02);
    color: #fff;
    box-shadow: 0 4px 15px rgba(230,98,57,0.4);
}
.step-item {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 16px;
}
.step-number {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e66239 0%, #ff8a65 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex-shrink: 0;
}
.step-number.step-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function submitKeyForm() {
    document.getElementById('getKeyForm').submit();
}
</script>
<?= $this->endSection() ?>
