<?= $this->extend('Layout/Auth') ?>

<?= $this->section('content') ?>
<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card" style="max-width:420px; width:100%;">
        <div class="card-body p-5">
            <div class="text-center mb-3">
                <a href="<?= site_url() ?>" class="mb-4 d-inline-block">
                    <img src="<?= base_url('assets/images/logo.svg') ?>" alt="Logo">
                </a>
                <h1 class="card-title mb-5 h5">Sign in to your account</h1>
            </div>

            <?php if (session()->getFlashdata('msgDanger')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <?= session()->getFlashdata('msgDanger') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('msgSuccess')) : ?>
                <div class="alert alert-success alert-dismissible show fade">
                    <?= session()->getFlashdata('msgSuccess') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?= form_open('', ['class' => 'needs-validation mt-3']) ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input id="username" type="text" class="form-control" name="username" placeholder="Enter your username" required minlength="4" value="<?= old('username') ?>" autofocus>
                    <?php if ($validation->hasError('username')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('username') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required minlength="6">
                    <?php if ($validation->hasError('password')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('password') ?></div>
                    <?php endif; ?>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input id="remember" class="form-check-input" type="checkbox" name="stay_log" value="yes">
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>
                </div>

                <button class="btn btn-primary w-100" type="submit">Sign in</button>
            <?= form_close() ?>

            <div class="text-center mt-3 small text-muted">
                Don't have an account? <a href="<?= site_url('register') ?>" class="link-primary">Sign up</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
