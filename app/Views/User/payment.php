<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Thanh toán hóa đơn</h3>
                <p class="text-subtitle text-muted">Mã hóa đơn: <strong><?= isset($invoice) ? esc(is_array($invoice) ? $invoice['invoice_code'] : $invoice->invoice_code) : 'N/A' ?></strong></p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <?php if (!isset($invoice)): ?>
                    <div class="alert alert-danger">
                        <h5 class="alert-heading"><i class="ti ti-circle-x"></i> Lỗi</h5>
                        <p>Không tìm thấy hóa đơn.</p>
                        <a href="<?= site_url('recharge') ?>" class="btn btn-primary">Quay lại</a>
                    </div>
                <?php elseif ($invoice->status == 'expired'): ?>
                    <div class="alert alert-danger">
                        <h5 class="alert-heading"><i class="ti ti-circle-x"></i> Hóa đơn đã hết hạn</h5>
                        <p>Hóa đơn này đã hết hạn. Vui lòng tạo hóa đơn mới.</p>
                        <a href="<?= site_url('recharge') ?>" class="btn btn-primary">Tạo hóa đơn mới</a>
                    </div>
                <?php elseif ($invoice->status == 'completed'): ?>
                    <div class="alert alert-success">
                        <h5 class="alert-heading"><i class="ti ti-check"></i> Thanh toán thành công</h5>
                        <p>Hóa đơn đã được thanh toán. Số dư của bạn đã được cập nhật.</p>
                        <a href="<?= site_url('recharge') ?>" class="btn btn-primary">Quay lại</a>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Thông tin thanh toán</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Mã hóa đơn:</strong></td>
                                            <td><h5><span class="badge bg-primary"><?= esc($invoice->invoice_code) ?></span></h5></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tổng tiền:</strong></td>
                                            <td><h4 class="text-success"><?= number_format($invoice->amount, 0, ',', '.') ?>₫</h4></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ngân hàng:</strong></td>
                                            <td><strong>MBBank</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Số tài khoản:</strong></td>
                                            <td><code style="font-size: 1.2em;">0868641019</code></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Chủ tài khoản:</strong></td>
                                            <td><strong>TRẦN VĂN HOÀNG</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nội dung CK:</strong></td>
                                            <td>
                                                <code style="font-size: 1.2em; color: #dc3545;"><?= esc($invoice->invoice_code) ?></code>
                                                <button class="btn btn-sm btn-outline-primary" onclick="copyContent('<?= esc($invoice->invoice_code) ?>')">
                                                    <i class="ti ti-clipboard"></i> Copy
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Hết hạn:</strong></td>
                                            <td>
                                                <span class="text-danger" id="countdown"></span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6 text-center">
                                    <h6>Quét mã QR để thanh toán</h6>
                                    <div class="mb-3">
                                        <img src="https://img.vietqr.io/image/MB-0868641019-compact2.png?amount=<?= $invoice->amount ?>&addInfo=<?= urlencode($invoice->invoice_code) ?>&accountName=TRAN%20VAN%20HOANG"
                                             alt="QR Code"
                                             class="img-fluid border rounded"
                                             style="max-width: 300px;">
                                    </div>
                                    <p class="text-muted">
                                        <small>Sử dụng ứng dụng ngân hàng để quét mã QR và thực hiện thanh toán</small>
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="alert alert-warning">
                                <i class="ti ti-alert-triangle"></i>
                                <strong>Lưu ý quan trọng:</strong>
                                <ul class="mb-0">
                                    <li>Chuyển khoản <strong>ĐÚNG số tiền</strong>: <?= number_format($invoice->amount, 0, ',', '.') ?>₫</li>
                                    <li>Nhập <strong>ĐÚNG nội dung</strong>: <code><?= esc($invoice->invoice_code) ?></code></li>
                                    <li>Hóa đơn có hiệu lực trong <strong>30 phút</strong></li>
                                </ul>
                            </div>

                            <div class="text-center">
                                <p class="text-muted mb-3">Hoàn thành thanh toán trước, sau đó nhấn nút bên dưới để kiểm tra trạng thái</p>
                                <button type="button" class="btn btn-success btn-lg" id="checkPaymentBtn" onclick="checkPayment()">
                                    <i class="ti ti-refresh"></i> Kiểm tra thanh toán
                                </button>
                                <div id="paymentStatus" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<script>
function copyContent(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Đã copy: ' + text);
    });
}

function checkPayment() {
    const btn = document.getElementById('checkPaymentBtn');
    const statusDiv = document.getElementById('paymentStatus');

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang kiểm tra...';

    fetch('<?= site_url("recharge/check/" . $invoice->invoice_code) ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.status === 'completed') {
                statusDiv.innerHTML = `
                    <div class="alert alert-success">
                        <h5><i class="ti ti-check"></i> ${data.message}</h5>
                        <p>Số dư mới: <strong>${data.new_balance}</strong></p>
                    </div>
                `;
                setTimeout(() => {
                    window.location.href = '<?= site_url("recharge") ?>';
                }, 2000);
            } else {
                statusDiv.innerHTML = `
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle"></i> ${data.message}
                    </div>
                `;
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-refresh"></i> Kiểm tra lại';
            }
        })
        .catch(error => {
            statusDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="ti ti-circle-x"></i> Lỗi kết nối. Vui lòng thử lại.
                </div>
            `;
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-refresh"></i> Kiểm tra lại';
        });
}

// Countdown timer
<?php
$expiredTimestamp = 0;
if (isset($invoice->expired_at) && $invoice->expired_at) {
    $expiredTimestamp = strtotime($invoice->expired_at) * 1000;
}
?>
const expiredAt = <?= $expiredTimestamp ?>;

if (expiredAt > 0) {
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = expiredAt - now;

        if (distance < 0) {
            document.getElementById('countdown').innerHTML = 'Đã hết hạn';
            document.getElementById('checkPaymentBtn').disabled = true;
            document.getElementById('checkPaymentBtn').innerHTML = '<i class="ti ti-clock-x"></i> Đã hết hạn';
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        document.getElementById('countdown').innerHTML = minutes + ' phút ' + seconds + ' giây';
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
} else if (document.getElementById('countdown')) {
    document.getElementById('countdown').innerHTML = '—';
}
</script>

<?= $this->endSection() ?>
