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
    <?php if ($currentPlan && !empty($currentPlan['plan_name'])) : ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Gói hiện tại: <?= esc($currentPlan['plan_name']) ?></h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4><?= (int)($currentPlan['max_packages'] ?? 0) ?>/<?= (int)($currentPlan['max_packages'] ?? 0) ?></h4>
                            <p class="text-muted mb-0">Package quota</p>
                        </div>
                        <div class="col-md-3">
                            <h4><?= (int)($currentPlan['max_keys'] ?? 0) ?>/<?= (int)($currentPlan['max_keys'] ?? 0) ?></h4>
                            <p class="text-muted mb-0">Key quota</p>
                        </div>
                        <div class="col-md-3">
                            <h4><?= date('d/m/Y', strtotime($currentPlan['expires_at'] ?? date('Y-m-d'))) ?></h4>
                            <p class="text-muted mb-0">Hết hạn</p>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#renewModal">
                                <i class="ti ti-refresh"></i> Gia hạn
                            </button>
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
                        <?php if ($plan->price_per_month > $currentPlan['plan_price']) : ?>
                            <form action="<?= site_url('plans/purchase') ?>" method="POST" class="purchase-form" data-plan-id="<?= esc($plan->id) ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="plan_id" value="<?= esc($plan->id) ?>">
                                <input type="hidden" name="duration_days" value="30" class="duration-input">

                                <select class="form-select duration-select mb-3" data-base-price="<?= esc($plan->price_per_month) ?>">
                                    <option value="30">30 ngày — <?= number_format($plan->price_per_month, 0, ',', '.') ?>₫</option>
                                    <option value="90">90 ngày — <?= number_format($plan->price_per_month * 3, 0, ',', '.') ?>₫</option>
                                    <option value="365">365 ngày — <?= number_format($plan->price_per_month * 12, 0, ',', '.') ?>₫</option>
                                </select>

                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Nâng cấp lên gói <?= esc($plan->name) ?>?')">
                                    <i class="ti ti-arrow-up"></i> Nâng cấp
                                </button>
                            </form>
                        <?php elseif ($plan->price_per_month == $currentPlan['plan_price']) : ?>
                            <button class="btn btn-secondary w-100" disabled>Đang sử dụng</button>
                        <?php else : ?>
                            <button class="btn btn-secondary w-100" disabled>Không thể hạ cấp</button>
                        <?php endif; ?>
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

<!-- Renew Modal -->
<?php if ($currentPlan) : ?>
<div class="modal fade" id="renewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gia hạn gói <?= esc($currentPlan['plan_name']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('plans/renew') ?>" method="POST" id="renewForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Chọn thời hạn</label>
                        <select class="form-select" name="duration_days" id="renewDuration" data-base-price="<?= esc($currentPlan['plan_price']) ?>">
                            <option value="30">30 ngày — <?= number_format($currentPlan['plan_price'], 0, ',', '.') ?>₫</option>
                            <option value="90">90 ngày — <?= number_format($currentPlan['plan_price'] * 3, 0, ',', '.') ?>₫</option>
                            <option value="365">365 ngày — <?= number_format($currentPlan['plan_price'] * 12, 0, ',', '.') ?>₫</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <small>Thời gian sẽ được cộng thêm từ ngày hết hạn hiện tại. Quota package và key sẽ được reset về 0.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning" id="renewBtn">Gia hạn</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

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

    if (e.target.matches('#renewDuration')) {
        var days = parseInt(e.target.value);
        var basePrice = parseInt(e.target.dataset.basePrice);
        var btn = document.getElementById('renewBtn');

        var totalPrice = (days === 30) ? basePrice : (days === 90) ? basePrice * 3 : basePrice * 12;
        btn.textContent = 'Gia hạn — ' + totalPrice.toLocaleString('vi-VN') + '₫';
    }
});
</script>
<?= $this->endSection() ?>
