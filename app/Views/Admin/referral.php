<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Create Referral Code</h3>
                <p class="text-subtitle text-muted">Generate referral codes for new users</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Referral</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-4">
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
                        <h4 class="card-title">Generate <?= $title ?></h4>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>

                        <div class="form-group mb-3">
                            <label for="set_saldo">Set Saldo (₹)</label>
                            <div class="input-group mt-2">
                                <span class="input-group-text"><i class="ti ti-coin"></i></span>
                                <input type="number" class="form-control" name="set_saldo" id="set_saldo" minlength="1" maxlength="11" value="5">
                            </div>
                            <?php if ($validation->hasError('set_saldo')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('set_saldo') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create Code</button>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <?php if ($code) : ?>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">History Generate - Total <?= $total_code ?></h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Referral Hash</th>
                                            <th>Saldo</th>
                                            <th>Used by</th>
                                            <th>Created by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($code as $c) : ?>
                                            <tr>
                                                <td><?= $c->id_reff ?></td>
                                                <td><code><?= substr($c->code, 1, 15) ?></code></td>
                                                <td><span class="badge bg-success">₹<?= $c->set_saldo ?></span></td>
                                                <td><?= $c->used_by ?: '—' ?></td>
                                                <td><?= $c->created_by ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
