<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Dashboard' ?> - <?= BASE_NAME ?></title>

    <?= $this->renderSection('stylesfirst') ?>

    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app-dark.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/iconly.css') ?>">

    <?= $this->renderSection('styles') ?>
</head>

<body>
    <script src="<?= base_url('assets/static/js/initTheme.js') ?>"></script>
    <div id="app">
        <div id="sidebar">
            <?= $this->include('Layout/Sidebar') ?>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <?= $this->renderSection('content') ?>

            <?= $this->include('Layout/Footer') ?>
        </div>
    </div>

    <script src="<?= base_url('assets/static/js/components/dark.js') ?>"></script>
    <script src="<?= base_url('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') ?>"></script>
    <script src="<?= base_url('assets/compiled/js/app.js') ?>"></script>
    <script>
    // Fix: sidebar closes on mobile scroll (resize from address bar show/hide)
    // Override the compiled app.js Sidebar onResize behavior
    if (window.Sidebar) {
        const origOnResize = window.Sidebar.prototype.onResize;
        window.Sidebar.prototype.onResize = function() {
            const isDesktop = window.innerWidth > 1200;
            if (isDesktop) {
                this.sidebarEL.classList.add("active");
                this.sidebarEL.classList.remove("inactive");
                this.deleteBackdrop();
                this.toggleOverflowBody(true);
            }
            // On mobile: do nothing — CSS media query handles sidebar visibility.
            // Prevents sidebar from closing when browser address bar hides/shows on scroll.
        };
    }
    </script>

    <?= $this->renderSection('js') ?>
</body>

</html>
