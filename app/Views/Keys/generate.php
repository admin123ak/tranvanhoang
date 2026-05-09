<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Generate License</h3>
                <p class="text-subtitle text-muted">Create new license key</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('keys') ?>">Keys</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Generate</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-8">
                <?php if (session()->getFlashdata('msgSuccess')) : ?>
                    <div class="alert alert-success alert-dismissible show fade">
                        <?= session()->getFlashdata('msgSuccess') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('user_key')) : ?>
                    <div class="alert alert-success alert-dismissible show fade">
                        <h5 class="alert-heading">License Generated Successfully!</h5>
                        <p><strong>Package:</strong> <?= session()->getFlashdata('game') ?> / <?= session()->getFlashdata('duration') ?> Hours</p>
                        <p><strong>License:</strong> <code class="key-sensi"><?= session()->getFlashdata('user_key') ?></code></p>
                        <p><strong>Devices:</strong> <?= session()->getFlashdata('max_devices') ?></p>
                        <hr>
                        <p class="mb-0"><small><i class="ti ti-info-circle"></i> Duration will start when license login.</small></p>
                        <p class="mb-0"><small><i class="ti ti-wallet"></i> Saldo Reduce: <span class="text-danger">-<?= session()->getFlashdata('fees') ?></span> (Total left - ₹ <?= $user->saldo ?>)</small></p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Create License</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="package_id" class="form-label">Package</label>
                                    <select class="form-select" name="package_id" id="package_id">
                                        <option value="">-- Select Package --</option>
                                        <?php foreach ($packages as $id => $name) : ?>
                                            <option value="<?= $id ?>" <?= old('package_id') == $id ? 'selected' : '' ?>><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if ($validation->hasError('package_id')) : ?>
                                        <div class="text-danger small mt-1"><?= $validation->getError('package_id') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="max_devices" class="form-label">Max Devices</label>
                                    <input type="number" name="max_devices" id="max_devices" class="form-control" placeholder="1" value="<?= old('max_devices') ?: 1 ?>" min="1">
                                    <?php if ($validation->hasError('max_devices')) : ?>
                                        <div class="text-danger small mt-1"><?= $validation->getError('max_devices') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="duration" class="form-label">Duration</label>
                            <select class="form-select" name="duration" id="duration">
                                <option value="">-- Select Duration --</option>
                                <?php foreach ($duration as $key => $val) : ?>
                                    <option value="<?= $key ?>" <?= old('duration') == $key ? 'selected' : '' ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($validation->hasError('duration')) : ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('duration') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-3">
                            <label for="estimation" class="form-label">Estimation</label>
                            <input type="text" id="estimation" class="form-control" placeholder="Your order will total" readonly>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Generate License</button>
                            <a href="<?= site_url('keys') ?>" class="btn btn-secondary">Back to Keys</a>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var price = JSON.parse('<?= $price ?>');
    getPrice(price);

    $("#max_devices, #duration, #package_id").change(function() {
        getPrice(price);
    });

    function getPrice(price) {
        var device = $("#max_devices").val();
        var durate = $("#duration").val();
        var gprice = price[durate];
        if (gprice != NaN && gprice) {
            var result = (device * gprice);
            $("#estimation").val('₹ ' + result);
        } else {
            $("#estimation").val('Select duration first');
        }
    }
});
</script>
<?= $this->endSection() ?>
