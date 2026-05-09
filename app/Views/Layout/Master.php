<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?= isset($title) ? $title : 'Dashboard' ?> - <?= BASE_NAME ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/images/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/images/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= base_url('assets/site.webmanifest') ?>">

    <?= $this->renderSection('stylesfirst') ?>

    <link rel="stylesheet" crossorigin href="<?= base_url('assets/css/main.css') ?>">

    <?= $this->renderSection('styles') ?>
</head>

<body>
    <div id="overlay" class="overlay"></div>

    <!-- TOPBAR -->
    <nav id="topbar" class="navbar bg-white border-bottom fixed-top topbar px-3">
        <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm">
            <i class="ti ti-layout-sidebar-left-expand"></i>
        </button>

        <!-- MOBILE -->
        <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2">
            <i class="ti ti-layout-sidebar-left-expand"></i>
        </button>
        <div>
            <!-- Navbar nav -->
            <ul class="list-unstyled d-flex align-items-center mb-0 gap-1">
                <!-- Bell icon -->
                <li>
                    <a class="position-relative btn-icon btn-sm btn-light btn rounded-circle" data-bs-toggle="dropdown"
                        aria-expanded="false" href="#" role="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-bell">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                            <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                        </svg>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-2 ms-n2">
                            2
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-0">
                        <ul class="list-unstyled p-0 m-0">
                            <li class="px-4 py-3 text-center">
                                <a href="#" class="text-primary">View all notifications</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- User Dropdown -->
                <li class="ms-3 dropdown">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= base_url('assets/images/avatar-1.jpg') ?>" alt="" class="avatar avatar-sm rounded-circle" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-0" style="min-width: 200px;">
                        <div class="p-3 d-flex flex-column gap-1 small lh-lg">
                            <a href="#!" class=""><span>Account Settings</span></a>
                            <a href="<?= site_url('logout') ?>" class=""><span>Logout</span></a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- SIDEBAR -->
    <?= $this->include('Layout/Sidebar') ?>

    <!-- MAIN CONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <script type="module" crossorigin src="<?= base_url('assets/js/main.js') ?>"></script>
    <?= $this->renderSection('js') ?>
</body>

</html>
