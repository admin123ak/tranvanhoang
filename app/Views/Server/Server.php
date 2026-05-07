<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Server Control</h3>
                <p class="text-subtitle text-muted">Manage server settings and mod configuration</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Server</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <?php
        include('conn.php');

        $sql1 = "select * from onoff where id=11";
        $result1 = mysqli_query($conn, $sql1);
        $userDetails1 = mysqli_fetch_assoc($result1);

        $sql2 = "select * from _ftext where id=1";
        $result2 = mysqli_query($conn, $sql2);
        $userDetails2 = mysqli_fetch_assoc($result2);

        $sql3 = "select * from modname where id=1";
        $result3 = mysqli_query($conn, $sql3);
        $userDetails3 = mysqli_fetch_assoc($result3);
        ?>

        <?php if (session()->getFlashdata('msgSuccess')) : ?>
            <div class="alert alert-success alert-dismissible show fade">
                <?= session()->getFlashdata('msgSuccess') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Server Online/Offline -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Mod Server Status</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>
                        <input type="hidden" name="status_form" value="1">

                        <div class="mb-3">
                            <label class="form-label">Current Status: <span class="badge bg-<?= $userDetails1['status'] == 'on' ? 'success' : 'danger' ?>"><?= strtoupper($userDetails1['status']) ?></span></label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="radios" id="radio1" value="1" required>
                            <label class="form-check-label" for="radio1">
                                <i class="bi bi-check-circle text-success"></i> Online Server
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="radios" id="radio2" value="2" required>
                            <label class="form-check-label" for="radio2">
                                <i class="bi bi-x-circle text-danger"></i> Offline Server
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="myInput" class="form-label">Current Offline Message: <small class="text-muted"><?= $userDetails1['myinput'] ?></small></label>
                            <textarea class="form-control" name="myInput" id="myInput" rows="2" placeholder="Enter Server Offline Message"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success">Update Status</button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <!-- Change Mod Name -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Mod Name</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>
                        <input type="hidden" name="modname_form" value="1">

                        <div class="mb-3">
                            <label for="modname" class="form-label">Current Mod Name: <small class="text-muted"><?= $userDetails3['modname'] ?? 'N/A' ?></small></label>
                            <input type="text" name="modname" id="modname" class="form-control" placeholder="Enter New Mod Name" required>
                            <?php if (isset($validation) && $validation->hasError('modname')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('modname') ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-danger">Update Mod Name</button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <!-- Change Mod Floating Text -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Mod Floating Text</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>
                        <input type="hidden" name="_ftext" value="1">

                        <div class="mb-3">
                            <label class="form-label">Current Mod Status: <span class="badge bg-<?= $userDetails2['_status'] == 'on' ? 'success' : 'danger' ?>"><?= strtoupper($userDetails2['_status']) ?></span></label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="_ftextr" id="ftext1" value="1" required>
                            <label class="form-check-label" for="ftext1">
                                <i class="bi bi-shield-check text-success"></i> MOD STATUS - SAFE
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="_ftextr" id="ftext2" value="2" required>
                            <label class="form-check-label" for="ftext2">
                                <i class="bi bi-shield-exclamation text-warning"></i> MOD STATUS - NOT SAFE
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="_ftext" class="form-label">Current Floating Text: <small class="text-muted"><?= $userDetails2['_ftext'] ?></small></label>
                            <input type="text" name="_ftextInput" id="_ftext" class="form-control" placeholder="Enter New Floating Text" required>
                            <?php if (isset($validation) && $validation->hasError('_ftextInput')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('_ftextInput') ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-warning">Update Floating Text</button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
