<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 text-center">
                <h3>Get Your Key</h3>
                <p class="text-subtitle text-muted">Nhấn nút bên dưới để nhận link + key của bạn</p>
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
                        <h5 class="fw-bold mb-1"><?= esc($config->package_name ?? 'Package') ?></h5>
                        <p class="text-muted small mb-3"><?= esc($config->pkg_code ?? '') ?></p>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="stat-box">
                                    <span class="d-block fw-bold text-primary"><?= $config->max_hours ?>h</span>
                                    <small class="text-muted">Thời hạn</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box">
                                    <span class="d-block fw-bold text-success"><?= $config->max_devices ?></span>
                                    <small class="text-muted">Thiết bị</small>
                                </div>
                            </div>
                        </div>

                        <?php if ($config->price_per_hour > 0) : ?>
                            <div class="alert alert-light-info mb-0">
                                <strong>Giá:</strong> <?= number_format($config->price_per_hour, 0) ?> VND/giờ
                            </div>
                        <?php else : ?>
                            <div class="alert alert-light-success mb-0">
                                <i class="ti ti-gift me-1"></i> <strong>MIỄN PHÍ</strong>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Get Link Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <h5 class="mb-3">Nhận Key + Link của bạn</h5>
                        <p class="text-muted mb-4">Mỗi người sẽ nhận được 1 key và 1 link riêng biệt</p>

                        <form action="<?= site_url('getkey/generate') ?>" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-getkey btn-lg">
                                <i class="ti ti-bolt me-1"></i> Lấy Link Ngay
                            </button>
                        </form>
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
</style>
<?= $this->endSection() ?>
