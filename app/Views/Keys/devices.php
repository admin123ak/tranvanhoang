<?= $this->extend('Layout/Master') ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Device Management</h3>
                <p class="text-subtitle text-muted">View all registered devices for license keys</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('keys') ?>">Keys</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Devices</li>
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Registered Devices</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($devices && count($devices) > 0) : ?>
                            <div class="table-responsive">
                                <table class="table table-striped" id="deviceTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>License Key</th>
                                            <th>Game/Package</th>
                                            <th>Device ID</th>
                                            <th>Devices Used</th>
                                            <th>Status</th>
                                            <th>Expired</th>
                                            <th>Registrator</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($devices as $index => $device) : ?>
                                            <?php
                                            $deviceList = $device->devices ? explode(',', trim($device->devices, ',')) : [];
                                            $deviceCount = count(array_filter($deviceList));
                                            ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <code class="key-blur"><?= esc($device->user_key) ?></code>
                                                </td>
                                                <td><?= esc($device->game) ?></td>
                                                <td>
                                                    <?php if ($deviceCount > 0) : ?>
                                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#deviceModal<?= $device->id_keys ?>">
                                                            <i class="bi bi-phone"></i> View (<?= $deviceCount ?>)
                                                        </button>
                                                    <?php else : ?>
                                                        <span class="text-muted">No devices</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $deviceCount >= $device->max_devices ? 'bg-danger' : 'bg-success' ?>">
                                                        <?= $deviceCount ?>/<?= $device->max_devices ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($device->status == 1) : ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else : ?>
                                                        <span class="badge bg-danger">Blocked</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($device->expired_date) : ?>
                                                        <?php
                                                        $expiry = \CodeIgniter\I18n\Time::parse($device->expired_date);
                                                        $isExpired = $expiry->isBefore(\CodeIgniter\I18n\Time::now());
                                                        ?>
                                                        <span class="badge <?= $isExpired ? 'bg-danger' : 'bg-secondary' ?>">
                                                            <?= $expiry->toLocalizedString('d MMM yy - H:mm') ?>
                                                        </span>
                                                    <?php else : ?>
                                                        <span class="text-muted">Not started</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= esc($device->registrator) ?></td>
                                                <td>
                                                    <a href="<?= site_url('keys/' . $device->id_keys) ?>" class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if ($deviceCount > 0) : ?>
                                                        <button class="btn btn-sm btn-danger" onclick="resetDevices('<?= $device->user_key ?>')" title="Reset Devices">
                                                            <i class="bi bi-bootstrap-reboot"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <!-- Device Modal -->
                                            <?php if ($deviceCount > 0) : ?>
                                                <div class="modal fade" id="deviceModal<?= $device->id_keys ?>" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Registered Devices - <?= esc($device->user_key) ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Device UUID</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php foreach ($deviceList as $idx => $deviceId) : ?>
                                                                                <?php if (trim($deviceId)) : ?>
                                                                                    <tr>
                                                                                        <td><?= $idx + 1 ?></td>
                                                                                        <td><code><?= esc($deviceId) ?></code></td>
                                                                                    </tr>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-light-info">
                                <i class="bi bi-info-circle"></i> No devices registered yet.
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">

<style>
.key-blur {
    filter: blur(3px);
    transition: filter 0.3s;
}
.key-blur:hover {
    filter: blur(0);
}
</style>

<script>
$(document).ready(function() {
    $('#deviceTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 25
    });
});

function resetDevices(userKey) {
    Swal.fire({
        title: 'Reset All Devices?',
        text: "This will remove all registered devices for this key!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, reset!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.getJSON('<?= site_url('keys/reset') ?>', {
                userkey: userKey,
                reset: 1
            }, function(data) {
                if (data.registered && data.reset) {
                    Swal.fire('Reset!', 'All devices have been removed.', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Failed!', 'Could not reset devices.', 'error');
                }
            });
        }
    });
}
</script>
<?= $this->endSection() ?>
