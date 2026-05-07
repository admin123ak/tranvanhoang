<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Settings</h3>
                <p class="text-subtitle text-muted">Manage your account settings</p>
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

        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Password</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>
                        <input type="hidden" name="password_form" value="1">

                        <div class="form-group mb-3">
                            <label for="current" class="form-label">Current Password</label>
                            <input type="password" name="current" id="current" class="form-control" placeholder="Current Password">
                            <?php if ($validation->hasError('current')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('current') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="New Password">
                            <?php if ($validation->hasError('password')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('password') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password2" class="form-label">Confirm Password</label>
                            <input type="password" name="password2" id="password2" class="form-control" placeholder="Confirm Password">
                            <?php if ($validation->hasError('password2')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('password2') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Account Information</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>
                        <input type="hidden" name="fullname_form" value="1">

                        <div class="form-group mb-3">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Your Full Name" value="<?= old('fullname') ?: ($user->fullname ?: '') ?>">
                            <?php if ($validation->hasError('fullname')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('fullname') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Update Account</button>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
