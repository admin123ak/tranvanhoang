<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Quản lý giá key</h3>
                <p class="text-subtitle text-muted">Danh sách duration đang được sử dụng trong hệ thống</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <!-- Existing Key Durations from keys_code -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="ti ti-key me-2"></i>Duration đang được sử dụng</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($keyDurations)) : ?>
                            <p class="text-muted">Chưa có key nào được tạo.</p>
                        <?php else : ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Duration (giờ)</th>
                                            <th>Số key đã tạo</th>
                                            <th>Lần cuối tạo</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($keyDurations as $kd) : ?>
                                            <?php
                                                // Check if this duration already has pricing
                                                $hasPricing = false;
                                                if (!empty($pricings)) {
                                                    foreach ($pricings as $p) {
                                                        if ($p->duration_hours == $kd->duration) {
                                                            $hasPricing = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                            ?>
                                            <tr>
                                                <td><strong><?= esc($kd->duration) ?>h</strong></td>
                                                <td><span class="badge bg-secondary"><?= esc($kd->total_keys) ?></span></td>
                                                <td><small><?= esc($kd->last_used) ?></small></td>
                                                <td>
                                                    <?php if ($hasPricing) : ?>
                                                        <span class="badge bg-success">Đã có giá</span>
                                                    <?php else : ?>
                                                        <span class="badge bg-warning">Chưa có giá</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!$hasPricing) : ?>
                                                        <button class="btn btn-sm btn-primary" onclick="quickAdd(<?= esc($kd->duration) ?>)">
                                                            <i class="ti ti-plus"></i> Thêm giá
                                                        </button>
                                                    <?php endif; ?>
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

            <!-- Pricing Management -->
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="ti ti-currency-dollar me-2"></i>Bảng giá key</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('admin/key-pricing/create') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <input type="number" class="form-control" name="duration_hours"
                                           placeholder="Giờ (VD: 24)" min="1" required>
                                </div>
                                <div class="col-4">
                                    <input type="number" class="form-control" name="price"
                                           placeholder="Giá (VND)" min="0" required>
                                </div>
                                <div class="col-4">
                                    <input type="text" class="form-control" name="description"
                                           placeholder="Mô tả (VD: 1 ngày)">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ti ti-plus"></i> Thêm giá
                            </button>
                        </form>

                        <hr>

                        <?php if (empty($pricings)) : ?>
                            <p class="text-muted text-center">Chưa có bảng giá nào. Hãy thêm từ danh sách bên trái.</p>
                        <?php else : ?>
                            <div class="list-group">
                                <?php foreach ($pricings as $p) : ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?= esc($p->duration_hours) ?>h</strong>
                                            <span class="badge bg-success ms-2"><?= number_format($p->price, 0, ',', '.') ?>₫</span>
                                            <?php if ($p->description) : ?>
                                                <small class="text-muted ms-2">- <?= esc($p->description) ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-warning" onclick="editPricing(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <a href="<?= site_url('admin/key-pricing/delete/' . $p->id) ?>"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Xóa giá này?')">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
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
                    <div class="form-group mb-2">
                        <label>Thời gian (giờ)</label>
                        <input type="number" class="form-control" name="duration_hours" id="edit_duration_hours" min="1" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Giá (VND)</label>
                        <input type="number" class="form-control" name="price" id="edit_price" min="0" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>Mô tả</label>
                        <input type="text" class="form-control" name="description" id="edit_description">
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
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

function quickAdd(durationHours) {
    document.querySelector('input[name="duration_hours"]').value = durationHours;
    document.querySelector('input[name="price"]').focus();
}
</script>

<?= $this->endSection() ?>
