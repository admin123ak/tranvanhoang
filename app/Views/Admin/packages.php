<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Package Management</h3>
                <p class="text-subtitle text-muted">Manage game packages and their IDs</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Packages</li>
                    </ol>
                </nav>
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

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Game Packages</h5>
                    <a href="<?= site_url('admin/packages/create') ?>" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Add Package
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Package Name</th>
                                <th>Package ID</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($packages) : ?>
                                <?php foreach ($packages as $pkg) : ?>
                                    <?php
                                    $id_package = is_object($pkg) ? $pkg->id_package : $pkg['id_package'];
                                    $package_name = is_object($pkg) ? $pkg->package_name : $pkg['package_name'];
                                    $package_id = is_object($pkg) ? $pkg->package_id : $pkg['package_id'];
                                    $description = is_object($pkg) ? $pkg->description : ($pkg['description'] ?? '');
                                    $status = is_object($pkg) ? $pkg->status : $pkg['status'];
                                    ?>
                                    <tr>
                                        <td><?= $id_package ?></td>
                                        <td><strong><?= esc($package_name) ?></strong></td>
                                        <td><code><?= esc($package_id) ?></code></td>
                                        <td><?= esc($description ?: '-') ?></td>
                                        <td>
                                            <?php if ($status == 1) : ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else : ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= site_url('admin/packages/edit/' . $id_package) ?>" class="btn btn-sm btn-primary">
                                                <i class="ti ti-edit"></i> Edit
                                            </a>
                                            <a href="<?= site_url('admin/packages/delete/' . $id_package) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this package?')">
                                                <i class="ti ti-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No packages found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
