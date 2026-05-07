<?= $this->extend('Layout/Master') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/extensions/simple-datatables/style.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/compiled/css/table-datatable.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Keys Management</h3>
                <p class="text-subtitle text-muted">Manage all your license keys</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Keys</li>
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

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Keys Registered</h5>
                    <div>
                        <button class="btn btn-sm btn-secondary" id="blur-out" data-bs-toggle="tooltip" title="Eye Protect">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                        <a href="<?= site_url('keys/generate') ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Generate
                        </a>
                        <a href="<?= site_url('keys/deleteExp') ?>" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i> Delete Expired
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if ($keylist) : ?>
                    <table class="table table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Game</th>
                                <th>User Keys</th>
                                <th>Devices</th>
                                <th>Duration</th>
                                <th>Expired</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                <?php else : ?>
                    <p class="text-center text-muted">No keys to show</p>
                <?php endif; ?>
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
.keyBlur.key-sensi {
    filter: blur(3px);
}
</style>

<script>
$(document).ready(function() {
    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        ajax: "<?= site_url('keys/api') ?>",
        columns: [{
                data: 'id',
                name: 'id_keys'
            },
            {
                data: 'game',
            },
            {
                data: 'user_key',
                render: function(data, type, row, meta) {
                    var is_valid = (row.status == 'Active') ? "text-success" : "text-danger";
                    return `<span class="${is_valid} keyBlur key-sensi">${(row.user_key ? row.user_key : '—')}</span>`;
                }
            },
            {
                data: 'devices',
                render: function(data, type, row, meta) {
                    var totalDevice = (row.devices ? row.devices : 0);
                    return `<span id="devMax-${row.user_key}">${totalDevice}/${row.max_devices}</span>`;
                }
            },
            {
                data: 'duration',
                render: function(data, type, row, meta) {
                    return row.duration;
                }
            },
            {
                data: 'expired',
                name: 'expired_date',
                render: function(data, type, row, meta) {
                    return row.expired ? `<span class="badge bg-secondary">${row.expired}</span>` : '<span class="text-muted">(not started yet)</span>';
                }
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    var btnReset = `<button class="btn btn-sm btn-danger" onclick="resetUserKey('${row.user_key}')" title="Reset key"><i class="bi bi-bootstrap-reboot"></i></button>`;
                    var btnEdits = `<a href="<?= site_url('keys') ?>/${row.id}" class="btn btn-sm btn-primary" title="Edit key"><i class="bi bi-pencil"></i></a>`;
                    return `${btnReset} ${btnEdits}`;
                }
            }
        ]
    });

    $("#blur-out").click(function() {
        if ($(".keyBlur").hasClass("key-sensi")) {
            $(".keyBlur").removeClass("key-sensi");
            $("#blur-out").html(`<i class="bi bi-eye"></i>`);
        } else {
            $(".keyBlur").addClass("key-sensi");
            $("#blur-out").html(`<i class="bi bi-eye-slash"></i>`);
        }
    });
});

function resetUserKey(keys) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, reset'
    }).then((result) => {
        if (result.isConfirmed) {
            var base_url = window.location.origin;
            var api_url = `${base_url}/keys/reset`;
            $.getJSON(api_url, {
                    userkey: keys,
                    reset: 1
                },
                function(data, textStatus, jqXHR) {
                    if (textStatus == 'success') {
                        if (data.registered) {
                            if (data.reset) {
                                $(`#devMax-${keys}`).html(`0/${data.devices_max}`);
                                Swal.fire('Reset!', 'Your device key has been reset.', 'success')
                            } else {
                                Swal.fire('Failed!', data.devices_total ? "You don't have any access to this user." : "User key devices already reset.", data.devices_total ? 'error' : 'warning')
                            }
                        } else {
                            Swal.fire('Failed!', "User key no longer exists.", 'error')
                        }
                    }
                }
            );
        }
    });
}
</script>
<?= $this->endSection() ?>
