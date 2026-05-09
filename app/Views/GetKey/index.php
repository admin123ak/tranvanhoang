<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

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
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-center">
                            <i class="ti ti-steps me-2"></i> Nhận Key
                        </h5>

                        <div id="step-container">
                            <!-- Step 1: Wait/Ad -->
                            <div id="step-wait" class="text-center py-3">
                                <p class="text-muted mb-3">Nhấn nút bên dưới để bắt đầu</p>
                                <button class="btn btn-getkey btn-lg w-100" onclick="startProcess()">
                                    <i class="ti ti-bolt me-1"></i> Bắt đầu nhận Key
                                </button>
                            </div>

                            <!-- Step 2: Loading/Processing -->
                            <div id="step-loading" class="text-center py-4 d-none">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="text-muted mb-1">Đang xử lý...</p>
                                <p class="small text-muted">Vui lòng chờ trong giây lát</p>
                            </div>

                            <!-- Step 3: Complete, show key -->
                            <div id="step-complete" class="text-center py-3 d-none">
                                <div class="alert alert-success mb-3">
                                    <i class="ti ti-check-circle me-1"></i> Hoàn tất! Key của bạn đã sẵn sàng
                                </div>
                                <form action="<?= site_url('getkey/create/' . $link->slug) ?>" method="POST" id="getKeyForm">
                                    <?= csrf_field() ?>
                                </form>
                                <button class="btn btn-success btn-lg w-100" onclick="submitKeyForm()">
                                    <i class="ti ti-key me-1"></i> Lấy Key Ngay
                                </button>
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
    padding: 14px 28px;
    font-size: 16px;
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
function startProcess() {
    // Hide step 1, show loading
    document.getElementById('step-wait').classList.add('d-none');
    document.getElementById('step-loading').classList.remove('d-none');

    // Simulate processing time (e.g., ad viewing, verification)
    setTimeout(function() {
        document.getElementById('step-loading').classList.add('d-none');
        document.getElementById('step-complete').classList.remove('d-none');
    }, 5000); // 5 seconds wait
}

function submitKeyForm() {
    document.getElementById('getKeyForm').submit();
}
</script>
<?= $this->endSection() ?>
