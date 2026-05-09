<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>API Tokens Management</h3>
                <p class="text-subtitle text-muted">Manage API tokens for auto key generation</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">API Tokens</li>
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
            <!-- Create Token Form -->
            <div class="col-12 col-lg-5 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="ti ti-plus"></i> Create New API Token
                        </h4>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Token Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   placeholder="e.g., Mobile App API" value="<?= old('name') ?>" required>
                            <?php if (isset($validation) && $validation->hasError('name')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('name') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="admin_account" class="form-label">Admin Account</label>
                            <select name="admin_account" id="admin_account" class="form-select" required>
                                <option value="">-- Select Admin Account --</option>
                                <?php if (is_array($adminUsers) && count($adminUsers) > 0) : ?>
                                    <?php foreach ($adminUsers as $admin) : ?>
                                        <?php
                                            $adminUsername = is_object($admin) ? ($admin->username ?? '') : ($admin['username'] ?? '');
                                            $adminSaldo = is_object($admin) ? ($admin->saldo ?? 0) : ($admin['saldo'] ?? 0);
                                        ?>
                                        <option value="<?= esc($adminUsername) ?>" <?= old('admin_account') == $adminUsername ? 'selected' : '' ?>>
                                            <?= esc($adminUsername) ?> (Balance: <?= number_format($adminSaldo, 0) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <option value="" disabled>No admin accounts found</option>
                                <?php endif; ?>
                            </select>
                            <?php if ($validation->hasError('admin_account')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('admin_account') ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Keys will be created using this admin account's balance</small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Create Token
                        </button>
                        <?= form_close() ?>
                    </div>
                </div>

                <!-- API Documentation -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="ti ti-book"></i> API Documentation
                        </h4>
                    </div>
                    <div class="card-body">
                        <h6>Generate Key Endpoint</h6>
                        <pre class="bg-light p-3 rounded"><code>POST <?= base_url('api/generate-key') ?>

Headers:
Authorization: Bearer {your_token}

Body (form-data):
- game: package_id
- duration: hours
- max_devices: number</code></pre>

                        <h6 class="mt-3">Check Balance Endpoint</h6>
                        <pre class="bg-light p-3 rounded"><code>GET <?= base_url('api/check-balance') ?>

Headers:
Authorization: Bearer {your_token}</code></pre>

                        <div class="alert alert-info mt-3">
                            <strong>Pricing:</strong> 1,000 VND per hour per device
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tokens List -->
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="ti ti-key"></i> Active API Tokens
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if ($tokens && count($tokens) > 0) : ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Admin Account</th>
                                            <th>Token</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tokens as $token) : ?>
                                            <tr>
                                                <td><?= $token->id ?></td>
                                                <td><strong><?= esc($token->name) ?></strong></td>
                                                <td><code><?= esc($token->admin_account) ?></code></td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm"
                                                               value="<?= esc($token->token) ?>"
                                                               id="token-<?= $token->id ?>" readonly>
                                                        <button class="btn btn-outline-secondary" type="button"
                                                                onclick="copyToken('token-<?= $token->id ?>')">
                                                            <i class="ti ti-copy"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($token->status == 1) : ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else : ?>
                                                        <span class="badge bg-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $time::parse($token->created_at)->toLocalizedString('d MMM yy') ?></td>
                                                <td>
                                                    <a href="<?= site_url('admin/api-tokens/toggle/' . $token->id) ?>"
                                                       class="btn btn-sm btn-warning"
                                                       title="Toggle Status">
                                                        <i class="ti ti-toggle-<?= $token->status == 1 ? 'right' : 'left' ?>"></i>
                                                    </a>
                                                    <a href="<?= site_url('admin/api-tokens/delete/' . $token->id) ?>"
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Are you sure you want to delete this token?')"
                                                       title="Delete">
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
                                <i class="ti ti-key-off fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No API tokens created yet</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
function copyToken(elementId) {
    const input = document.getElementById(elementId);
    input.select();
    input.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(input.value).then(function() {
        alert('Token copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
<?= $this->endSection() ?>
