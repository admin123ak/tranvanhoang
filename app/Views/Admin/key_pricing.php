<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Quản lý giá key</h3>
                <p class="text-subtitle text-muted">Thiết lập giá theo thời gian sử dụng</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Thêm giá key</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('admin/key-pricing/create') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="duration_hours">Thời gian (giờ) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="duration_hours" name="duration_hours"
                                               placeholder="VD: 24, 168, 720..." min="1" required>
                                        <small class="text-muted">24h = 1 ngày, 168h = 7 ngày, 720h = 30 ngày</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price">Giá (VND) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="price" name="price"
                                               placeholder="VD: 10000, 50000..." min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">Mô tả</label>
                                        <input type="text" class="form-control" id="description" name="description"
                                               placeholder="VD: 1 ngày, 1 tuần...">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Thêm giá
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Danh sách giá key</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($pricings)): ?>
                            <p class="text-muted">Chưa có giá nào</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Thời gian</th>
                                            <th>Giá</th>
                                            <th>Mô tả</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pricings as $pricing): ?>
                                            <tr>
                                                <td><?= esc($pricing->id) ?></td>
                                                <td><strong><?= esc($pricing->duration_hours) ?> giờ</strong></td>
                                                <td><span class="badge bg-success"><?= number_format($pricing->price, 0, ',', '.') ?>₫</span></td>
                                                <td><?= esc($pricing->description ?? '—') ?></td>
                                                <td>
                                                    <?php if ($pricing->status == 1): ?>
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Tắt</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" onclick="editPricing(<?= htmlspecialchars(json_encode($pricing), ENT_QUOTES, 'UTF-8') ?>)">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <a href="<?= site_url('admin/key-pricing/delete/' . $pricing->id) ?>"
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Xóa giá này?')">
                                                        <i class="ti ti-trash"></i>
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
                <h5 class="modal-title">Sửa giá key</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Thời gian (giờ) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="duration_hours" id="edit_duration_hours" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Giá (VND) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="price" id="edit_price" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <input type="text" class="form-control" name="description" id="edit_description">
                    </div>
                    <div class="form-group">
                        <label>Trạng thái <span class="text-danger">*</span></label>
                        <select class="form-control" name="status" id="edit_status" required>
                            <option value="1">Hoạt động</option>
                            <option value="0">Tắt</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editPricing(pricing) {
    document.getElementById('edit_duration_hours').value = pricing.duration_hours;
    document.getElementById('edit_price').value = pricing.price;
    document.getElementById('edit_description').value = pricing.description || '';
    document.getElementById('edit_status').value = pricing.status;
    document.getElementById('editForm').action = '<?= site_url('admin/key-pricing/edit/') ?>' + pricing.id;

    var modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}
</script>

<?= $this->endSection() ?>
