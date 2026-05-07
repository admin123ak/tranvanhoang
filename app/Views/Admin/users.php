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
                <h3>Manage Users</h3>
                <p class="text-subtitle text-muted">Search users by username, fullname, saldo or uplink</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Manage <?= $title ?></h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Fullname</th>
                            <th>Level</th>
                            <th>Saldo</th>
                            <th>Status</th>
                            <th>Uplink</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">

<script>
$(document).ready(function() {
    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        ajax: "<?= site_url('admin/api/users') ?>",
        columns: [{
                data: 'id',
            },
            {
                data: 'username',
            },
            {
                data: 'fullname',
                render: function(data, type, row, meta) {
                    return (row.fullname ? row.fullname : '~');
                }
            },
            {
                data: 'level',
            },
            {
                data: 'saldo',
                render: function(data, type, row, meta) {
                    var textc = (row.level === 'Admin' ? 'primary' : 'dark');
                    var saldo = (row.level === 'Admin' ? '∞' : row.saldo);
                    return `<span class="badge bg-${textc}">${saldo}</span>`;
                }
            },
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row, meta) {
                    var act = `<span class="badge bg-success">Active</span>`;
                    var ban = `<span class="badge bg-danger">Banned</span>`;
                    return (row.status == 1 ? act : ban);
                }
            },
            {
                data: 'uplink',
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    return `<a href="<?= site_url('admin/user') ?>/${row.id}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit</a>`;
                }
            }
        ]
    });
});
</script>
<?= $this->endSection() ?>
