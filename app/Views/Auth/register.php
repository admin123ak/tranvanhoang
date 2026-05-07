<?= $this->extend('Layout/Auth') ?>

<?= $this->section('content') ?>
<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="<?= site_url() ?>"><img src="<?= base_url('assets/static/images/logo/logo.svg') ?>" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Sign Up</h1>
            <p class="auth-subtitle mb-5">Input your data to register to our website.</p>

            <?php if (session()->getFlashdata('msgDanger')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <?= session()->getFlashdata('msgDanger') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?= form_open() ?>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" name="username" placeholder="Username" required minlength="4" maxlength="24" value="<?= old('username') ?>">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <?php if ($validation->hasError('username')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('username') ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" name="password" placeholder="Password" required minlength="6" maxlength="24">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <?php if ($validation->hasError('password')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('password') ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" name="password2" placeholder="Confirm Password" required minlength="6" maxlength="24">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <?php if ($validation->hasError('password2')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('password2') ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" name="referral" placeholder="Referral Code (Optional)" maxlength="25" value="<?= old('referral') ?>">
                    <div class="form-control-icon">
                        <i class="bi bi-gift"></i>
                    </div>
                    <?php if ($validation->hasError('referral')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('referral') ?></div>
                    <?php endif; ?>
                    <small class="text-muted">Enter referral code to get bonus saldo</small>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Sign Up</button>
            <?= form_close() ?>

            <div class="text-center mt-5 text-lg fs-4">
                <p class="text-gray-600">Already have an account? <a href="<?= site_url('login') ?>" class="font-bold">Log in</a>.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right"></div>
    </div>
</div>
<?= $this->endSection() ?>
