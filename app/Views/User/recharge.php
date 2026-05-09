<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Nạp tiền</h3>
                <p class="text-subtitle text-muted">Nạp tiền tự động qua MBBank</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Tạo hóa đơn nạp tiền</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <h5>Số dư hiện tại</h5>
                            <h2 class="mb-0"><?= isset($user->saldo) ? number_format($user->saldo, 0, ',', '.') : '0' ?>₫</h2>
                        </div>

                        <form action="<?= site_url('recharge/create') ?>" method="POST" id="createInvoiceForm">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="amount">Số tiền cần nạp (VND)</label>
                                <input type="number" class="form-control" id="amount" name="amount"
                                       placeholder="Nhập số tiền (tối thiểu 10.000₫)"
                                       min="10000" step="1000" required>
                                <small class="text-muted">Tối thiểu: 10.000₫</small>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="ti ti-receipt"></i> Tạo hóa đơn
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Recent Invoices -->
                <div class="card">
                    <div class="card-header">
                        <h4>Hóa đơn gần đây</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($invoices) || !is_array($invoices)): ?>
                            <p class="text-muted">Chưa có hóa đơn nào</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Mã HĐ</th>
                                            <th>Số tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Thời gian</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($invoices as $inv): ?>
                                            <tr>
                                                <td><code><?= isset($inv->invoice_code) ? esc($inv->invoice_code) : 'N/A' ?></code></td>
                                                <td><strong><?= isset($inv->amount) ? number_format($inv->amount, 0, ',', '.') : '0' ?>₫</strong></td>
                                                <td>
                                                    <?php if (!isset($inv->status) || $inv->status == 'pending'): ?>
                                                        <span class="badge bg-warning">Chờ thanh toán</span>
                                                    <?php elseif ($inv->status == 'completed'): ?>
                                                        <span class="badge bg-success">Hoàn thành</span>
                                                    <?php elseif ($inv->status == 'expired'): ?>
                                                        <span class="badge bg-danger">Hết hạn</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Đã hủy</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= isset($inv->created_at) ? esc($inv->created_at) : 'N/A' ?></td>
                                                <td>
                                                    <?php if (isset($inv->status) && $inv->status == 'pending' && isset($inv->invoice_code)): ?>
                                                        <a href="<?= site_url('recharge/payment/' . $inv->invoice_code) ?>"
                                                           class="btn btn-sm btn-primary">
                                                            <i class="ti ti-credit-card"></i> Thanh toán
                                                        </a>
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

            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Hướng dẫn</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">Cách nạp tiền:</h6>
                            <ol class="mb-0">
                                <li>Nhập số tiền cần nạp</li>
                                <li>Nhấn "Tạo hóa đơn"</li>
                                <li>Chuyển khoản theo thông tin hiển thị</li>
                                <li>Nhấn "Kiểm tra thanh toán"</li>
                                <li>Tiền sẽ được cộng tự động</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Transaction History -->
                <div class="card">
                    <div class="card-header">
                        <h4>Lịch sử giao dịch</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($transactions) || !is_array($transactions)): ?>
                            <p class="text-muted">Chưa có giao dịch nào</p>
                        <?php else: ?>
                            <div class="list-group">
                                <?php $txn_slice = array_slice($transactions, 0, 5); ?>
                                <?php foreach ($txn_slice as $txn): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">
                                                    <?php if (isset($txn->type) && $txn->type == 'IN'): ?>
                                                        <span class="badge bg-success">+<?= isset($txn->amount) ? number_format($txn->amount, 0, ',', '.') : '0' ?>₫</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">-<?= isset($txn->amount) ? number_format($txn->amount, 0, ',', '.') : '0' ?>₫</span>
                                                    <?php endif; ?>
                                                </h6>
                                                <small class="text-muted"><?= isset($txn->transaction_date) ? esc($txn->transaction_date) : 'N/A' ?></small>
                                            </div>
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

<script>
// Prevent double form submission
document.getElementById('createInvoiceForm').addEventListener('submit', function(e) {
    var btn = document.getElementById('submitBtn');
    if (btn.disabled) {
        e.preventDefault();
        return;
    }
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';
});

// Auto refresh invoice status every 15 seconds if there's a pending invoice
<?php if (!empty($invoices) && is_array($invoices)): ?>
    <?php foreach ($invoices as $inv): ?>
        <?php if (isset($inv->status) && $inv->status == 'pending'): ?>
            setInterval(function() {
                location.reload();
            }, 15000);
            <?php break; ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
</script>

<?= $this->endSection() ?>