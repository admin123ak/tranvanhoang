<?= $this->extend('Layout/Auth') ?>

<?= $this->section('content') ?>
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card" style="max-width:420px; width:100%;">
        <div class="card-body p-5">
            <div class="text-center mb-3">
                <a href="<?= site_url() ?>" class="mb-4 d-inline-block">
                    <img src="<?= base_url('assets/images/logo.svg') ?>" alt="Logo">
                </a>
                <h1 class="card-title mb-5 h5">Create your account</h1>
            </div>

            <?php if (session()->getFlashdata('msgDanger')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <?= session()->getFlashdata('msgDanger') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?= form_open('', ['class' => 'needs-validation mt-3']) ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input id="username" type="text" class="form-control" name="username" placeholder="Enter username" required minlength="4" maxlength="24" value="<?= old('username') ?>">
                    <?php if ($validation->hasError('username')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('username') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control" name="password" placeholder="Create a password" required minlength="6" maxlength="24">
                    <?php if ($validation->hasError('password')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('password') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="password2" class="form-label">Confirm password</label>
                    <input id="password2" type="password" class="form-control" name="password2" placeholder="Repeat password" required minlength="6" maxlength="24">
                    <?php if ($validation->hasError('password2')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('password2') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="referral" class="form-label">Referral Code (Optional)</label>
                    <input id="referral" type="text" class="form-control" name="referral" placeholder="Enter referral code" maxlength="25" value="<?= old('referral') ?>">
                    <?php if ($validation->hasError('referral')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('referral') ?></div>
                    <?php endif; ?>
                    <small class="text-muted">Enter referral code to get bonus saldo</small>
                </div>

                <button class="btn btn-primary w-100" type="submit">Sign up</button>
            <?= form_close() ?>

            <div class="text-center mt-3 small text-muted">
                Already have an account? <a href="<?= site_url('login') ?>" class="link-primary">Sign in</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
