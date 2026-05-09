<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Quản lý Gói thành viên</h3>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('msgSuccess')) : ?>
        <div class="alert alert-success alert-dismissible show fade"><?= session()->getFlashdata('msgSuccess') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('msgDanger')) : ?>
        <div class="alert alert-danger alert-dismissible show fade"><?= session()->getFlashdata('msgDanger') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <!-- Add form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h4>Thêm gói mới</h4></div>
                <div class="card-body">
                    <form action="<?= site_url('admin/plans/create') ?>" method="POST" class="row g-3">
                        <?= csrf_field() ?>
                        <div class="col-md-2">
                            <label class="form-label">Tên gói</label>
                            <input type="text" class="form-control" name="name" placeholder="Basic, Pro..." required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Giá/tháng (VNĐ)</label>
                            <input type="number" class="form-control" name="price_per_month" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Max Package</label>
                            <input type="number" class="form-control" name="max_packages" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Max Key</label>
                            <input type="number" class="form-control" name="max_keys" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" name="status">
                                <option value="1">Hoạt động</option>
                                <option value="0">Tắt</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Mô tả</label>
                            <input type="text" class="form-control" name="description">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Thêm gói</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Plans list -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Danh sách gói</h4>
                    <span class="badge bg-primary"><?= count($plans) ?> gói</span>
                    <span class="badge bg-success ms-2"><?= esc($activeSubscriptions) ?> thuê bao hoạt động</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Giá/tháng</th>
                                <th>Max Package</th>
                                <th>Max Key</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($plans as $plan) : ?>
                            <tr>
                                <td><?= esc($plan->id) ?></td>
                                <td><strong><?= esc($plan->name) ?></strong></td>
                                <td><?= number_format($plan->price_per_month, 0, ',', '.') ?>₫</td>
                                <td><?= esc($plan->max_packages) ?></td>
                                <td><?= esc($plan->max_keys) ?></td>
                                <td><?= $plan->status == 1 ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-secondary">Tắt</span>' ?></td>
                                <td>
                                    <a href="<?= site_url('admin/plans/delete/' . $plan->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa gói này?')">Xóa</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
