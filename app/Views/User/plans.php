<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Gói Thành Viên</h3>
                <p class="text-subtitle text-muted">Mua gói để tạo package và key miễn phí</p>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('msgSuccess')) : ?>
        <div class="alert alert-success alert-dismissible show fade">
            <?= session()->getFlashdata('msgSuccess') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('msgDanger')) : ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <?= session()->getFlashdata('msgDanger') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('msgWarning')) : ?>
        <div class="alert alert-warning alert-dismissible show fade">
            <?= session()->getFlashdata('msgWarning') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Current Plan Status -->
    <?php if ($currentPlan) : ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Gói hiện tại: <?= esc($currentPlan['plan_name']) ?></h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h4><?= $currentPlan['packages_left'] ?>/<?= $currentPlan['max_packages'] ?></h4>
                            <p class="text-muted mb-0">Package còn lại</p>
                        </div>
                        <div class="col-md-4">
                            <h4><?= $currentPlan['keys_left'] ?>/<?= $currentPlan['max_keys'] ?></h4>
                            <p class="text-muted mb-0">Key còn lại</p>
                        </div>
                        <div class="col-md-4">
                            <h4><?= date('d/m/Y', strtotime($currentPlan['expires_at'])) ?></h4>
                            <p class="text-muted mb-0">Hết hạn</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Available Plans -->
    <div class="row">
        <?php foreach ($plans as $plan) : ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0"><?= esc($plan->name) ?></h5>
                </div>
                <div class="card-body">
                    <p class="text-muted text-center"><?= esc($plan->description ?? '') ?></p>
                    <ul class="list-unstyled text-center mb-3">
                        <li><i class="ti ti-package text-primary me-2"></i><strong><?= $plan->max_packages ?></strong> package(s)</li>
                        <li><i class="ti ti-key text-primary me-2"></i><strong><?= $plan->max_keys ?></strong> key(s)</li>
                        <li><i class="ti ti-wallet text-primary me-2"></i><?= number_format($plan->price_per_month, 0, ',', '.') ?>₫ / 30 ngày</li>
                    </ul>

                    <?php if ($currentPlan) : ?>
                        <button class="btn btn-secondary w-100" disabled>Đang có gói <?= esc($currentPlan['plan_name']) ?></button>
                    <?php else : ?>
                        <form action="<?= site_url('plans/purchase') ?>" method="POST" class="purchase-form" data-plan-id="<?= esc($plan->id) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="plan_id" value="<?= esc($plan->id) ?>">
                            <input type="hidden" name="duration_days" value="30" class="duration-input">

                            <select class="form-select duration-select mb-3" data-base-price="<?= esc($plan->price_per_month) ?>">
                                <option value="30">30 ngày — <?= number_format($plan->price_per_month, 0, ',', '.') ?>₫</option>
                                <option value="90">90 ngày — <?= number_format($plan->price_per_month * 3, 0, ',', '.') ?>₫</option>
                                <option value="365">365 ngày — <?= number_format($plan->price_per_month * 12, 0, ',', '.') ?>₫</option>
                            </select>

                            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Mua gói <?= esc($plan->name) ?>?')">
                                Mua gói
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('change', function(e) {
    if (e.target.matches('.duration-select')) {
        var days = parseInt(e.target.value);
        var basePrice = parseInt(e.target.dataset.basePrice);
        var form = e.target.closest('.purchase-form');
        var durationInput = form.querySelector('.duration-input');
        var btn = form.querySelector('button[type="submit"]');

        var totalPrice = (days === 30) ? basePrice : (days === 90) ? basePrice * 3 : basePrice * 12;
        durationInput.value = days;
        btn.textContent = 'Mua gói — ' + totalPrice.toLocaleString('vi-VN') + '₫';
    }
});
</script>
<?= $this->endSection() ?>
