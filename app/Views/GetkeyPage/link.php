<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 text-center">
                <h3>Link đã được tạo!</h3>
                <p class="text-subtitle text-muted">Vào link bên dưới để nhận key</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Success Icon -->
                <div class="text-center mb-4">
                    <div class="success-icon mb-3">
                        <i class="ti ti-link"></i>
                    </div>
                </div>

                <!-- Link Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 text-center">
                            <i class="ti ti-external-link me-2"></i> Link của bạn
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-light-info mb-3">
                            <i class="ti ti-info-circle me-1"></i> Nhấn vào link bên dưới, xem quảng cáo, sau đó bạn sẽ nhận được key
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Link YeuMoney:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="<?= esc($yeumoneyUrl) ?>" id="linkUrl" readonly>
                                <button class="btn btn-primary" type="button" onclick="copyText('linkUrl')">
                                    <i class="ti ti-copy"></i> Copy
                                </button>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="<?= esc($yeumoneyUrl) ?>" class="btn btn-getkey btn-lg" target="_blank">
                                <i class="ti ti-external-link me-1"></i> Vào Link Nhận Key
                            </a>
                        </div>

                        <div class="alert alert-light-warning mt-3 mb-0">
                            <small><i class="ti ti-clock me-1"></i> Link có hiệu lực trong 10 phút</small>
                        </div>
                    </div>
                </div>

                <!-- Package Info -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Thông tin Package:</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Package</small>
                                    <strong><?= esc($config->package_name ?? 'N/A') ?></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Thời hạn</small>
                                    <strong><?= isset($pricing) ? esc($pricing->duration_hours) : esc($config->max_hours) ?> giờ</strong>
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
                                    <small class="text-muted d-block">Giá</small>
                                    <?php $price = isset($pricing) ? $pricing->price : ($config->price_per_hour * $config->max_hours); ?>
                                    <?php if ($price > 0) : ?>
                                        <strong><?= number_format($price, 0, ',', '.') ?>₫</strong>
                                    <?php else : ?>
                                        <span class="badge bg-success">FREE</span>
                                    <?php endif; ?>
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
    background: linear-gradient(135deg, #e66239 0%, #ff8a65 100%);
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
