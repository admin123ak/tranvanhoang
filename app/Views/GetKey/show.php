<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 text-center">
                <h3>Your Key</h3>
                <p class="text-subtitle text-muted">Thông tin key của bạn</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 text-center">
                            <i class="ti ti-key me-2"></i> Key Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Key Code:</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg font-monospace" value="<?= esc($key->user_key) ?>" id="keyCode" readonly>
                                <button class="btn btn-primary" type="button" onclick="copyText('keyCode')">
                                    <i class="ti ti-copy"></i> Copy
                                </button>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Package</small>
                                    <strong><?= esc($packageName) ?></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Game</small>
                                    <strong><?= esc($key->game) ?></strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Thời hạn</small>
                                    <strong><?= $key->duration ?> giờ</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <small class="text-muted d-block">Thiết bị</small>
                                    <strong><?= $key->max_devices ?> device</strong>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-box">
                                    <small class="text-muted d-block">Trạng thái</small>
                                    <?php if ($key->status == 1) : ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-light-info mt-4 mb-0">
                            <i class="ti ti-info-circle me-1"></i> Lưu key này để sử dụng trong game
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
.info-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
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
