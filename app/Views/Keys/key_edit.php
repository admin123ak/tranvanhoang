<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Key</h3>
                <p class="text-subtitle text-muted">Edit key information</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('keys') ?>">Keys</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
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

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Key Information</h4>
                    </div>
                    <div class="card-body">
                        <?= form_open('keys/edit') ?>

                        <input type="hidden" name="id_keys" value="<?= $key->id_keys ?>">

                        <?php if ($user->level == 1) : ?>
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="game" class="form-label">Games</label>
                                    <input type="text" name="game" id="game" class="form-control" placeholder="Game Name" value="<?= old('game') ?: $key->game ?>">
                                    <?php if ($validation->hasError('game')) : ?>
                                        <div class="text-danger small mt-1"><?= $validation->getError('game') ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="user_key" class="form-label">User Key</label>
                                    <input type="text" name="user_key" id="user_key" class="form-control" placeholder="License Key" value="<?= old('user_key') ?: $key->user_key ?>">
                                    <?php if ($validation->hasError('user_key')) : ?>
                                        <div class="text-danger small mt-1"><?= $validation->getError('user_key') ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="duration" class="form-label">Duration <small class="text-muted">(in hours)</small></label>
                                    <input type="number" name="duration" id="duration" class="form-control" placeholder="24" value="<?= old('duration') ?: $key->duration ?>">
                                    <?php if ($validation->hasError('duration')) : ?>
                                        <div class="text-danger small mt-1"><?= $validation->getError('duration') ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="max_devices" class="form-label">Max Devices</label>
                                    <input type="number" name="max_devices" id="max_devices" class="form-control" placeholder="1" value="<?= old('max_devices') ?: $key->max_devices ?>">
                                    <?php if ($validation->hasError('max_devices')) : ?>
                                        <div class="text-danger small mt-1"><?= $validation->getError('max_devices') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status">
                                    <option value="">— Select Status —</option>
                                    <option value="0" <?= $key->status == 0 ? 'selected' : '' ?>>Banned/Block</option>
                                    <option value="1" <?= $key->status == 1 ? 'selected' : '' ?>>Active</option>
                                </select>
                                <?php if ($validation->hasError('status')) : ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('status') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="registrator" class="form-label">Registrator</label>
                                <input type="text" name="registrator" id="registrator" class="form-control" placeholder="Username" value="<?= old('registrator') ?: $key->registrator ?>">
                                <?php if ($validation->hasError('registrator')) : ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('registrator') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="expired_date" class="form-label">Expired <?= !$key->expired_date ? '(Not started yet)' : '' ?></label>
                                <input type="text" name="expired_date" id="expired_date" class="form-control" placeholder="<?= $time::now() ?>" value="<?= old('expired_date') ?: $key->expired_date ?>">
                                <?php if ($validation->hasError('expired_date')) : ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('expired_date') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-lg-12 mb-3">
                                <label for="devices" class="form-label">Devices <span class="badge bg-info maxDev"><?= $key_info->total ?>/<?= $key->max_devices ?></span> <small class="text-muted">(Separately with enter)</small></label>
                                <textarea class="form-control" name="devices" id="devices" rows="<?= ($key_info->total > $key->max_devices) ? 3 : ($key_info->total ?: 3) ?>"><?= old('devices') ?: ($key_info->total ? $key_info->devices : '') ?></textarea>
                                <?php if ($validation->hasError('devices')) : ?>
                                    <div class="text-danger small mt-1"><?= $validation->getError('devices') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-lg-12">
                                <button class="btn btn-primary btnUpdate" disabled>Update User Key</button>
                                <a href="<?= site_url('keys') ?>" class="btn btn-secondary">Back to Keys</a>
                            </div>
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
    var level = "<?= $user->level ?>";
    if (level != 1) $("#registrator, #expired_date, #devices").attr('disabled', true);
    $("input, select, textarea").change(function() {
        $(".btnUpdate").attr('disabled', false);
    });
});
var total = "<?= $key_info->total ?>";
$("#max_devices").change(function() {
    $(".maxDev").html(total + '/' + $(this).val());
    $("#devices").attr('rows', $(this).val());
});
</script>
<?= $this->endSection() ?>
