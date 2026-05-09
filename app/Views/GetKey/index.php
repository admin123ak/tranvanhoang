<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12">
                <h3>Get Key - Auto Generation</h3>
                <p class="text-subtitle text-muted">Chọn package và nhấn "Get Key" - Không cần nhập gì cả</p>
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

        <?php if (is_array($configs) && count($configs) > 0) : ?>
            <div class="row g-4">
                <?php foreach ($configs as $cfg) : ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card getkey-card h-100">
                            <div class="card-body text-center p-4">
                                <div class="getkey-icon mb-3">
                                    <i class="ti ti-key"></i>
                                </div>
                                <h5 class="card-title fw-bold"><?= esc($cfg->package_name ?? 'Package') ?></h5>
                                <p class="text-muted small mb-3"><?= esc($cfg->pkg_code ?? '') ?></p>

                                <div class="row g-2 mb-4">
                                    <div class="col-4">
                                        <div class="stat-box">
                                            <span class="d-block fw-bold text-primary"><?= number_format($cfg->price_per_hour, 0) ?></span>
                                            <small class="text-muted">VND/H</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-box">
                                            <span class="d-block fw-bold text-success"><?= $cfg->max_hours ?>h</span>
                                            <small class="text-muted">Duration</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-box">
                                            <span class="d-block fw-bold text-info"><?= $cfg->max_devices ?></span>
                                            <small class="text-muted">Devices</small>
                                        </div>
                                    </div>
                                </div>

                                <form action="<?= site_url('getkey/create/' . $cfg->id) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-getkey w-100">
                                        <i class="ti ti-bolt me-1"></i> Get Key
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="text-center py-5">
                <i class="ti ti-key-off fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">Không có package nào khả dụng</h5>
                <p class="text-muted">Vui lòng liên hệ admin để được hỗ trợ</p>
            </div>
        <?php endif; ?>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.getkey-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}
.getkey-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}
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
