<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Quan ly Goi thanh vien</h3>
                <p class="text-subtitle text-muted">Them, sua, xoa cac goi subscription</p>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('msgSuccess')) : ?>
        <div class="alert alert-success alert-dismissible show fade">
            <?= session()->getFlashdata('msgSuccess') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('msgDanger')) : ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <?= session()->getFlashdata('msgDanger') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Them goi moi</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('admin/plans/create') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Ten goi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               placeholder="VD: Basic, Pro, Advanced" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="price_per_month">Gia/thang (VNĐ) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="price_per_month" name="price_per_month"
                                               placeholder="VD: 100000" required>
                                        <small class="text-muted">30 ngay = gia goc, 90 ngay = x3, 365 ngay = x12</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="max_packages">Max Package <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="max_packages" name="max_packages"
                                               placeholder="VD: 1" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="max_keys">Max Key <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="max_keys" name="max_keys"
                                               placeholder="VD: 20" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">Mo ta</label>
                                        <input type="text" class="form-control" id="description" name="description"
                                               placeholder="VD: Goi co ban cho nguoi moi">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Trang thai</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="1">Hoat dong</option>
                                            <option value="0">Tat</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="ti ti-plus"></i> Them goi
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Danh sach goi</h4>
                        <span class="badge bg-primary"><?= count($plans) ?> goi</span>
                        <span class="badge bg-success ms-2"><?= $activeSubscriptions ?> thue bao aktif</span>
                    </div>
                    <div class="card-body">
                        <?php if (empty($plans)): ?>
                            <p class="text-muted">Chua co goi nao</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Ten</th>
                                            <th>Gia/thang</th>
                                            <th>Max Package</th>
                                            <th>Max Key</th>
                                            <th>Trang thai</th>
                                            <th>Hanh dong</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($plans as $plan): ?>
                                            <tr>
                                                <td><?= esc($plan->id) ?></td>
                                                <td><strong><?= esc($plan->name) ?></strong></td>
                                                <td><?= number_format($plan->price_per_month, 0, ',', '.') ?>d</td>
                                                <td><?= esc($plan->max_packages) ?></td>
                                                <td><?= esc($plan->max_keys) ?></td>
                                                <td>
                                                    <?php if ($plan->status == 1): ?>
                                                        <span class="badge bg-success">Hoat dong</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Tat</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning edit-plan-btn"
                                                            data-id="<?= esc($plan->id) ?>"
                                                            data-name="<?= esc($plan->name) ?>"
                                                            data-price="<?= esc($plan->price_per_month) ?>"
                                                            data-max-packages="<?= esc($plan->max_packages) ?>"
                                                            data-max-keys="<?= esc($plan->max_keys) ?>"
                                                            data-description="<?= esc($plan->description ?? '') ?>"
                                                            data-status="<?= esc($plan->status) ?>">
                                                        <i class="ti ti-edit"></i> Sua
                                                    </button>
                                                    <a href="<?= site_url('admin/plans/delete/' . $plan->id) ?>"
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Xoa goi nay?')">
                                                        <i class="ti ti-trash"></i> Xoa
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sua goi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST" action="">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Ten goi</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Gia/thang (VNĐ)</label>
                        <input type="number" class="form-control" name="price_per_month" id="edit_price_per_month" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Max Package</label>
                        <input type="number" class="form-control" name="max_packages" id="edit_max_packages" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Max Key</label>
                        <input type="number" class="form-control" name="max_keys" id="edit_max_keys" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Mo ta</label>
                        <input type="text" class="form-control" name="description" id="edit_description">
                    </div>
                    <div class="form-group mb-3">
                        <label>Trang thai</label>
                        <select class="form-control" name="status" id="edit_status" required>
                            <option value="1">Hoat dong</option>
                            <option value="0">Tat</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Dong</button>
                    <button type="submit" class="btn btn-primary">Luu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.edit-plan-btn');
    if (btn) {
        e.preventDefault();
        var id = btn.getAttribute('data-id');
        var name = btn.getAttribute('data-name');
        var price = btn.getAttribute('data-price');
        var maxPackages = btn.getAttribute('data-max-packages');
        var maxKeys = btn.getAttribute('data-max-keys');
        var description = btn.getAttribute('data-description');
        var status = btn.getAttribute('data-status');

        document.getElementById('edit_name').value = name;
        document.getElementById('edit_price_per_month').value = price;
        document.getElementById('edit_max_packages').value = maxPackages;
        document.getElementById('edit_max_keys').value = maxKeys;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_status').value = status;
        document.getElementById('editForm').action = '<?= site_url('admin/plans/edit/') ?>' + id;

        var modalEl = document.getElementById('editModal');
        var modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>
<?= $this->endSection() ?>
