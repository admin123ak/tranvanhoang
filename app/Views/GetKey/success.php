<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 text-center">
                <h3>Key Created Successfully!</h3>
                <p class="text-subtitle text-muted">Key của bạn đã sẵn sàng sử dụng</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="success-icon mb-4">
                            <i class="ti ti-check"></i>
                        </div>

                        <h4 class="fw-bold mb-2">Key của bạn đã sẵn sàng!</h4>
                        <p class="text-muted mb-4">Package: <strong><?= esc($packageName) ?></strong></p>

                        <div class="key-display mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg text-center fw-bold"
                                       value="<?= esc($key['user_key']) ?>" id="generatedKey" readonly
                                       style="font-size: 1.5rem; letter-spacing: 3px;">
                                <button class="btn btn-primary px-4" type="button" onclick="copyKey()">
                                    <i class="ti ti-copy me-1"></i> Copy
                                </button>
                            </div>
                        </div>

                        <div class="row g-3 mb-4 text-start">
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Thời hạn</small>
                                    <strong><?= $key['duration'] ?> Giờ</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Thiết bị</small>
                                    <strong><?= $key['max_devices'] ?></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Game</small>
                                    <strong><?= esc($key['game']) ?></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Ngày tạo</small>
                                    <strong><?= date('d/m/Y H:i') ?></strong>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                            <a href="<?= site_url('get/' . $link->slug) ?>" class="btn btn-outline-primary px-4">
                                <i class="ti ti-arrow-left me-1"></i> Tạo Key Khác
                            </a>
                            <?php if (session()->get('userid')) : ?>
                                <a href="<?= site_url('keys') ?>" class="btn btn-primary px-4">
                                    <i class="ti ti-list-check me-1"></i> Xem Tất Cả Key
                                </a>
                            <?php endif; ?>
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
.key-display input {
    background: #f8f9fa;
    border: 2px dashed #e9ecef;
    border-radius: 12px;
}
.info-box {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 12px 16px;
}
.info-box strong {
    font-size: 1.1rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function copyKey() {
    const input = document.getElementById('generatedKey');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function() {
        alert('Key copied to clipboard!');
    });
}
</script>
<?= $this->endSection() ?>
