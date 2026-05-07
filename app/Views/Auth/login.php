<?= $this->extend('Layout/Auth') ?>

<?= $this->section('content') ?>
<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="<?= site_url() ?>"><img src="<?= base_url('assets/static/images/logo/logo.svg') ?>" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Log in.</h1>
            <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>

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

            <?= form_open() ?>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" name="username" placeholder="Username" required minlength="4" value="<?= old('username') ?>">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <?php if ($validation->hasError('username')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('username') ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" name="password" placeholder="Password" required minlength="6">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <?php if ($validation->hasError('password')) : ?>
                        <div class="text-danger small mt-1"><?= $validation->getError('password') ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <input class="form-check-input me-2" type="checkbox" name="stay_log" value="yes" id="flexCheckDefault">
                    <label class="form-check-label text-gray-600" for="flexCheckDefault">
                        Keep me logged in
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>
            <?= form_close() ?>

            <div class="text-center mt-5 text-lg fs-4">
                <p class="text-gray-600">Don't have an account? <a href="<?= site_url('register') ?>" class="font-bold">Sign up</a>.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right"></div>
    </div>
</div>
<?= $this->endSection() ?>
