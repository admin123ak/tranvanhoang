<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit User</h3>
                <p class="text-subtitle text-muted">Edit account information for <?= getName($target) ?></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/manage-users') ?>">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-8">
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

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Account · <?= getName($target) ?></h4>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>
                        <input type="hidden" name="user_id" value="<?= $target->id_users ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" value="<?= old('username') ?: $target->username ?>">
                                <?php if ($validation->hasError('username')) : ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('username') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fullname" class="form-label">Fullname</label>
                                <input type="text" name="fullname" id="fullname" class="form-control" value="<?= old('fullname') ?: $target->fullname ?>">
                                <?php if ($validation->hasError('fullname')) : ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('fullname') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="level" class="form-label">Roles</label>
                                <select class="form-select" name="level" id="level">
                                    <option value="">— Select Roles —</option>
                                    <option value="1" <?= $target->level == 1 ? 'selected' : '' ?>>Admin</option>
                                    <option value="2" <?= $target->level == 2 ? 'selected' : '' ?>>Reseller</option>
                                </select>
                                <?php if ($validation->hasError('level')) : ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('level') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status">
                                    <option value="">— Select Status —</option>
                                    <option value="0" <?= $target->status == 0 ? 'selected' : '' ?>>Banned/Block</option>
                                    <option value="1" <?= $target->status == 1 ? 'selected' : '' ?>>Active</option>
                                </select>
                                <?php if ($validation->hasError('status')) : ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('status') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="saldo" class="form-label">Saldo (₹)</label>
                                <input type="number" name="saldo" id="saldo" class="form-control" value="<?= old('saldo') ?: $target->saldo ?>">
                                <?php if ($validation->hasError('saldo')) : ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('saldo') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="uplink" class="form-label">Uplink</label>
                                <input type="text" name="uplink" id="uplink" class="form-control" value="<?= old('uplink') ?: $target->uplink ?>">
                                <?php if ($validation->hasError('uplink')) : ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('uplink') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary">Update Account Information</button>
                                <a href="<?= site_url('admin/manage-users') ?>" class="btn btn-secondary">Back to Users</a>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
