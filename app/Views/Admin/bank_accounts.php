<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Quản lý tài khoản ngân hàng</h3>
                <p class="text-subtitle text-muted">Thêm, sửa, xóa tài khoản ngân hàng và API token</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Thêm tài khoản ngân hàng</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('admin/bank-accounts/create') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bank_name">Tên ngân hàng <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bank_name" name="bank_name"
                                               placeholder="VD: MBBank, VietcomBank..." required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_number">Số tài khoản <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="account_number" name="account_number"
                                               placeholder="VD: 0868641019" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="account_name">Tên chủ tài khoản <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="account_name" name="account_name"
                                               placeholder="VD: TRAN VAN HOANG" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="api_token">API Token</label>
                                        <input type="text" class="form-control" id="api_token" name="api_token"
                                               placeholder="VD: MB_FREE_021FA4D804026B08">
                                        <small class="text-muted">Để trống nếu không có</small>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Thêm tài khoản
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Danh sách tài khoản ngân hàng</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($accounts)): ?>
                            <p class="text-muted">Chưa có tài khoản ngân hàng nào</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Ngân hàng</th>
                                            <th>Số TK</th>
                                            <th>Chủ TK</th>
                                            <th>API Token</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($accounts as $acc): ?>
                                            <tr>
                                                <td><?= esc($acc->id) ?></td>
                                                <td><strong><?= esc($acc->bank_name) ?></strong></td>
                                                <td><code><?= esc($acc->account_number) ?></code></td>
                                                <td><?= esc($acc->account_name) ?></td>
                                                <td>
                                                    <?php if (!empty($acc->api_token)): ?>
                                                        <code><?= esc(substr($acc->api_token, 0, 20)) ?>...</code>
                                                    <?php else: ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($acc->status == 1): ?>
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Tắt</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning" onclick="editAccount(<?= htmlspecialchars(json_encode($acc), ENT_QUOTES, 'UTF-8') ?>)">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <a href="<?= site_url('admin/bank-accounts/delete/' . $acc->id) ?>"
                                                       class="btn btn-sm btn-danger"
                                                       onclick="return confirm('Xóa tài khoản này?')">
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
                <h5 class="modal-title">Sửa tài khoản ngân hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tên ngân hàng <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="bank_name" id="edit_bank_name" required>
                    </div>
                    <div class="form-group">
                        <label>Số tài khoản <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="account_number" id="edit_account_number" required>
                    </div>
                    <div class="form-group">
                        <label>Tên chủ tài khoản <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="account_name" id="edit_account_name" required>
                    </div>
                    <div class="form-group">
                        <label>API Token</label>
                        <input type="text" class="form-control" name="api_token" id="edit_api_token">
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
function editAccount(account) {
    document.getElementById('edit_bank_name').value = account.bank_name;
    document.getElementById('edit_account_number').value = account.account_number;
    document.getElementById('edit_account_name').value = account.account_name;
    document.getElementById('edit_api_token').value = account.api_token || '';
    document.getElementById('edit_status').value = account.status;
    document.getElementById('editForm').action = '<?= site_url('admin/bank-accounts/edit/') ?>' + account.id;

    var modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}
</script>

<?= $this->endSection() ?>
