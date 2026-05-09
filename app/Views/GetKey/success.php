<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 text-center">
                <h3>Link + Key đã được tạo!</h3>
                <p class="text-subtitle text-muted">Lưu link bên dưới để xem key bất cứ lúc nào</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Success Icon -->
                <div class="text-center mb-4">
                    <div class="success-icon mb-3">
                        <i class="ti ti-circle-check"></i>
                    </div>
                </div>

                <!-- Link Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 text-center">
                            <i class="ti ti-link me-2"></i> Link của bạn
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <?php $displayUrl = $shortUrl ?: $fullUrl; ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Link xem key:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="<?= esc($displayUrl) ?>" id="linkUrl" readonly>
                                <button class="btn btn-primary" type="button" onclick="copyText('linkUrl')">
                                    <i class="ti ti-copy"></i> Copy
                                </button>
                            </div>
                            <small class="text-muted">Lưu link này để xem key bất cứ lúc nào</small>
                        </div>

                        <?php if ($shortUrl) : ?>
                            <div class="alert alert-light-success">
                                <i class="ti ti-check me-1"></i> Link đã được rút gọn qua YeuMoney
                            </div>
                        <?php endif; ?>

                        <div class="text-center mt-3">
                            <a href="<?= esc($displayUrl) ?>" class="btn btn-success" target="_blank">
                                <i class="ti ti-external-link me-1"></i> Mở Link
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Key Preview Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header py-3">
                        <h5 class="mb-0">
                            <i class="ti ti-key me-2"></i> Thông tin Key
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Key Code:</label>
                            <div class="input-group">
                                <input type="text" class="form-control font-monospace" value="<?= esc($userKey) ?>" id="keyCode" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyText('keyCode')">
                                    <i class="ti ti-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Package</small>
                                    <strong><?= esc($packageName) ?></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Thời hạn</small>
                                    <strong><?= $config->max_hours ?> giờ</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Thiết bị</small>
                                    <strong><?= $config->max_devices ?> device</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Trạng thái</small>
                                    <span class="badge bg-success">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.success-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.success-icon i {
    font-size: 40px;
    color: #fff;
}
.info-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    text-align: center;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function copyText(elementId) {
    const input = document.getElementById(elementId);
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function() {
        alert('Copied!');
    });
}
</script>
<?= $this->endSection() ?>
