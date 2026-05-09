<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>API Configuration</h3>
                <p class="text-subtitle text-muted">Setup auto key generation for GetKey page</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">API Config</li>
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

        <div class="row">
            <!-- Config Form -->
            <div class="col-12 col-lg-5 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="ti ti-settings"></i> Add New Config
                        </h4>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>

                        <div class="form-group mb-3">
                            <label for="admin_account" class="form-label">Admin Account</label>
                            <select name="admin_account" id="admin_account" class="form-select" required>
                                <option value="">-- Select Admin Account --</option>
                                <?php if (is_array($adminUsers)) : ?>
                                    <?php foreach ($adminUsers as $admin) : ?>
                                        <?php $aUser = is_object($admin) ? ($admin->username ?? '') : ($admin['username'] ?? ''); ?>
                                        <?php $aSaldo = is_object($admin) ? ($admin->saldo ?? 0) : ($admin['saldo'] ?? 0); ?>
                                        <option value="<?= esc($aUser) ?>" <?= old('admin_account') == $aUser ? 'selected' : '' ?>>
                                            <?= esc($aUser) ?> (Balance: <?= number_format($aSaldo, 0) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="package_id" class="form-label">Package</label>
                            <select name="package_id" id="package_id" class="form-select" required>
                                <option value="">-- Select Package --</option>
                                <?php if (is_array($packages)) : ?>
                                    <?php foreach ($packages as $pkg) : ?>
                                        <?php $pId = is_object($pkg) ? ($pkg->id_package ?? '') : ($pkg['id_package'] ?? ''); ?>
                                        <?php $pName = is_object($pkg) ? ($pkg->package_name ?? '') : ($pkg['package_name'] ?? ''); ?>
                                        <?php $pCode = is_object($pkg) ? ($pkg->package_id ?? '') : ($pkg['package_id'] ?? ''); ?>
                                        <option value="<?= $pId ?>" <?= old('package_id') == $pId ? 'selected' : '' ?>>
                                            <?= esc($pName) ?> (<?= esc($pCode) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price_per_hour" class="form-label">Price per Hour (VND)</label>
                            <input type="number" name="price_per_hour" id="price_per_hour" class="form-control"
                                   value="<?= old('price_per_hour', 1000) ?>" min="100" step="100" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="max_hours" class="form-label">Max Hours per Key</label>
                            <input type="number" name="max_hours" id="max_hours" class="form-control"
                                   value="<?= old('max_hours', 720) ?>" min="1" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="max_devices" class="form-label">Max Devices per Key</label>
                            <input type="number" name="max_devices" id="max_devices" class="form-control"
                                   value="<?= old('max_devices', 1) ?>" min="1" max="20" required>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Add Config
                        </button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <!-- Configs List -->
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="ti ti-list-details"></i> Active Configurations
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if ($configs && count($configs) > 0) : ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Admin Account</th>
                                            <th>Package</th>
                                            <th>Price/Hour</th>
                                            <th>Max Hours</th>
                                            <th>Max Devices</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($configs as $cfg) : ?>
                                            <tr>
                                                <td><?= $cfg->id ?></td>
                                                <td><code><?= esc($cfg->admin_account) ?></code></td>
                                                <td><?= esc($cfg->package_name ?? $cfg->package_id) ?></td>
                                                <td><?= number_format($cfg->price_per_hour, 0) ?> VND</td>
                                                <td><?= $cfg->max_hours ?>h</td>
                                                <td><?= $cfg->max_devices ?></td>
                                                <td>
                                                    <?php if ($cfg->status == 1) : ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else : ?>
                                                        <span class="badge bg-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?= site_url('admin/api-config/toggle/' . $cfg->id) ?>"
                                                       class="btn btn-sm btn-warning" title="Toggle Status">
                                                        <i class="ti ti-toggle-<?= $cfg->status == 1 ? 'right' : 'left' ?>"></i>
                                                    </a>
                                                    <a href="<?= site_url('admin/api-config/delete/' . $cfg->id) ?>"
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Are you sure?')" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <div class="text-center py-5">
                                <i class="ti ti-settings-off fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No configurations yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
