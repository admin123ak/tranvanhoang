<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Goi Thanh Vien</h3>
                <p class="text-subtitle text-muted">Mua goi de tao package va key mien phi</p>
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
                        <h5 class="mb-0"><i class="ti ti-crown me-2"></i> Goi hien tai: <?= esc($currentPlan['plan_name']) ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4><?= $currentPlan['packages_left'] ?>/<?= $currentPlan['max_packages'] ?></h4>
                                    <p class="text-muted mb-0">Package con lai</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4><?= $currentPlan['keys_left'] ?>/<?= $currentPlan['max_keys'] ?></h4>
                                    <p class="text-muted mb-0">Key con lai</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4><?= date('d/m/Y', strtotime($currentPlan['expires_at'])) ?></h4>
                                    <p class="text-muted mb-0">Het han</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4><?= number_format($currentPlan['plan_price'], 0, ',', '.') ?>d</h4>
                                    <p class="text-muted mb-0">Gia/thang</p>
                                </div>
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
                    <div class="card-body text-center">
                        <p class="text-muted mb-3"><?= esc($plan->description ?? '') ?></p>

                        <div class="text-start mb-4">
                            <div class="mb-2">
                                <i class="ti ti-package text-primary me-2"></i>
                                <strong><?= $plan->max_packages ?></strong> package(s)
                            </div>
                            <div class="mb-2">
                                <i class="ti ti-key text-primary me-2"></i>
                                <strong><?= $plan->max_keys ?></strong> key(s)
                            </div>
                        </div>

                        <?php if ($currentPlan) : ?>
                            <button class="btn btn-secondary btn-block w-100" disabled>
                                Dang co goi <?= esc($currentPlan['plan_name']) ?>
                            </button>
                        <?php else : ?>
                            <!-- Duration Selection -->
                            <div class="form-group mb-3 text-start">
                                <label><strong>Thoi han:</strong></label>
                                <select class="form-select duration-select" data-plan-id="<?= esc($plan->id) ?>" data-base-price="<?= esc($plan->price_per_month) ?>">
                                    <option value="30">30 ngay &mdash; <?= number_format($plan->price_per_month, 0, ',', '.') ?>d</option>
                                    <option value="90">90 ngay &mdash; <?= number_format($plan->price_per_month * 3, 0, ',', '.') ?>d</option>
                                    <option value="365">365 ngay &mdash; <?= number_format($plan->price_per_month * 12, 0, ',', '.') ?>d</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <span class="h3 plan-price-display"><?= number_format($plan->price_per_month, 0, ',', '.') ?>d</span>
                            </div>

                            <form action="<?= site_url('plans/purchase') ?>" method="POST" class="d-inline purchase-form" data-plan-id="<?= esc($plan->id) ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="plan_id" value="<?= esc($plan->id) ?>">
                                <input type="hidden" name="duration_days" value="30" class="duration-input">
                                <button type="submit" class="btn btn-primary btn-block w-100"
                                        onclick="return confirm('Mua goi <?= esc($plan->name) ?>?')">
                                    <i class="ti ti-shopping-cart me-2"></i> Mua ngay
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
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.duration-select').forEach(function(select) {
        select.addEventListener('change', function() {
            var days = parseInt(this.value);
            var basePrice = parseInt(this.getAttribute('data-base-price'));
            var card = this.closest('.card');
            var priceDisplay = card.querySelector('.plan-price-display');
            var form = card.querySelector('.purchase-form');
            var durationInput = form.querySelector('.duration-input');

            var totalPrice;
            if (days === 30) {
                totalPrice = basePrice;
            } else if (days === 90) {
                totalPrice = basePrice * 3;
            } else {
                totalPrice = basePrice * 12;
            }

            priceDisplay.textContent = totalPrice.toLocaleString('vi-VN') + 'd';
            durationInput.value = days;
        });
    });
});
</script>
<?= $this->endSection() ?>
