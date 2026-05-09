<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>GetKey Links Management</h3>
                <p class="text-subtitle text-muted">Tạo link getkey cố định cho người dùng</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">GetKey Links</li>
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
            <!-- Create Link Form -->
            <div class="col-12 col-lg-5 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="ti ti-link"></i> Tạo GetKey Link Mới
                        </h4>
                    </div>
                    <div class="card-body">
                        <?= form_open('admin/getkey-links/create') ?>

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Tên Link</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   placeholder="VD: Free PUBG Key" value="<?= old('name') ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="admin_account" class="form-label">Tài khoản Admin</label>
                            <select name="admin_account" id="admin_account" class="form-select" required>
                                <option value="">-- Chọn Admin --</option>
                                <?php if (is_array($adminUsers) && count($adminUsers) > 0) : ?>
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
                                <option value="">-- Chọn Package --</option>
                                <?php if (is_array($packages) && count($packages) > 0) : ?>
                                    <?php foreach ($packages as $pkg) : ?>
                                        <?php $pId = is_object($pkg) ? ($pkg->id_package ?? '') : ($pkg['id_package'] ?? ''); ?>
                                        <?php $pName = is_object($pkg) ? ($pkg->package_name ?? '') : ($pkg['package_name'] ?? ''); ?>
                                        <option value="<?= $pId ?>" <?= old('package_id') == $pId ? 'selected' : '' ?>>
                                            <?= esc($pName) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="youmoney_token" class="form-label">YouMoney API Token <small class="text-muted">(tùy chọn)</small></label>
                            <input type="text" name="youmoney_token" id="youmoney_token" class="form-control"
                                   value="<?= old('youmoney_token') ?>" placeholder="Nhập API token YouMoney nếu có">
                        </div>

                        <div class="form-group mb-3">
                            <label for="price_per_hour" class="form-label">Giá mỗi giờ (VND) - <strong>0 = FREE</strong></label>
                            <input type="number" name="price_per_hour" id="price_per_hour" class="form-control"
                                   value="<?= old('price_per_hour', 0) ?>" min="0" step="100">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="max_hours" class="form-label">Số giờ</label>
                                    <input type="number" name="max_hours" id="max_hours" class="form-control"
                                           value="<?= old('max_hours', 720) ?>" min="1" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="max_devices" class="form-label">Số thiết bị</label>
                                    <input type="number" name="max_devices" id="max_devices" class="form-control"
                                           value="<?= old('max_devices', 1) ?>" min="1" max="20" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Tạo Link
                        </button>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>

            <!-- Links List -->
            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="ti ti-list-details"></i> Danh sách GetKey Links
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if ($links && count($links) > 0) : ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tên</th>
                                            <th>Short Link (YeuMoney)</th>
                                            <th>Admin</th>
                                            <th>Package</th>
                                            <th>Giá/H</th>
                                            <th>Giờ</th>
                                            <th>Devices</th>
                                            <th>Đã tạo</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($links as $link) : ?>
                                            <tr>
                                                <td>
                                                    <strong><?= esc($link->name) ?></strong><br>
                                                    <small class="text-muted"><i class="ti ti-link"></i> /get/<?= esc($link->slug) ?></small>
                                                </td>
                                                <td>
                                                    <?php $shortUrl = $link->short_url ?? null; if ($shortUrl) : ?>
                                                        <a href="<?= esc($shortUrl) ?>" target="_blank" class="text-success fw-semibold small">
                                                            <i class="ti ti-external-link"></i> <?= esc($shortUrl) ?>
                                                        </a><br>
                                                        <div class="input-group input-group-sm mt-1" style="max-width:250px;">
                                                            <input type="text" class="form-control form-control-sm" value="<?= esc($shortUrl) ?>" id="shortUrl-<?= $link->id ?>" readonly>
                                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyText('shortUrl-<?= $link->id ?>')">
                                                                <i class="ti ti-copy"></i>
                                                            </button>
                                                        </div>
                                                    <?php else : ?>
                                                        <span class="text-muted small">No short URL</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><code><?= esc($link->admin_account) ?></code></td>
                                                <td><span class="badge bg-secondary"><?= esc($link->package_name ?? $link->package_id) ?></span></td>
                                                <td><?= $link->price_per_hour == 0 ? '<span class="text-success fw-bold">FREE</span>' : number_format($link->price_per_hour, 0) . ' VND' ?></td>
                                                <td><?= $link->max_hours ?>h</td>
                                                <td><?= $link->max_devices ?></td>
                                                <td><span class="badge bg-info"><?= $link->total_keys_created ?></span></td>
                                                <td>
                                                    <?php if ($link->status == 1) : ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else : ?>
                                                        <span class="badge bg-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($link->youmoney_token) : ?>
                                                        <a href="<?= site_url('admin/getkey-links/reshorten/' . $link->id) ?>"
                                                           class="btn btn-sm btn-info mb-1" title="Re-shorten URL">
                                                            <i class="ti ti-refresh"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="<?= site_url('admin/getkey-links/toggle/' . $link->id) ?>"
                                                       class="btn btn-sm btn-warning mb-1" title="Toggle">
                                                        <i class="ti ti-toggle-<?= $link->status == 1 ? 'right' : 'left' ?>"></i>
                                                    </a>
                                                    <a href="<?= site_url('admin/getkey-links/delete/' . $link->id) ?>"
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Xóa link này?')" title="Delete">
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
                                <i class="ti ti-link-off fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Chưa có GetKey link nào</p>
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
function copyText(elementId) {
    const input = document.getElementById(elementId);
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(function() {
        alert('Copied!');
    });
}
</script>
<?= $this->endSection() ?>
