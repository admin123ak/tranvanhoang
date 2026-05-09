<aside id="sidebar" class="sidebar">
    <div class="logo-area">
        <a href="<?= site_url('dashboard') ?>" class="d-inline-flex">
            <img src="<?= base_url('assets/images/logo.svg') ?>" alt="Logo">
        </a>
    </div>
    <ul class="nav flex-column">
        <li class="px-4 py-2"><small class="nav-text">Main</small></li>

        <li>
            <a class="nav-link <?= (uri_string() == 'dashboard') ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>">
                <i class="ti ti-home"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>

        <li>
            <a class="nav-link <?= (strpos(uri_string(), 'keys') !== false) ? 'active' : '' ?>" href="<?= site_url('keys') ?>">
                <i class="ti ti-key"></i>
                <span class="nav-text">Keys</span>
            </a>
        </li>

        <?php if (isset($user) && $user->level == 1) : ?>
        <li class="px-4 pt-4 pb-2"><small class="nav-text">Admin</small></li>

        <li>
            <a class="nav-link <?= (uri_string() == 'Server') ? 'active' : '' ?>" href="<?= site_url('Server') ?>">
                <i class="ti ti-server"></i>
                <span class="nav-text">Server Control</span>
            </a>
        </li>

        <li>
            <a class="nav-link <?= (strpos(uri_string(), 'admin/packages') !== false) ? 'active' : '' ?>" href="<?= site_url('admin/packages') ?>">
                <i class="ti ti-box-seam"></i>
                <span class="nav-text">Packages</span>
            </a>
        </li>

        <li>
            <a class="nav-link <?= (strpos(uri_string(), 'admin/manage-users') !== false) ? 'active' : '' ?>" href="<?= site_url('admin/manage-users') ?>">
                <i class="ti ti-users"></i>
                <span class="nav-text">Manage Users</span>
            </a>
        </li>
        <?php endif; ?>

        <li class="px-4 pt-4 pb-2"><small class="nav-text">Developer</small></li>

        <li>
            <a class="nav-link" href="<?= site_url('api-docs') ?>">
                <i class="ti ti-file-text"></i>
                <span class="nav-text">API Documentation</span>
            </a>
        </li>

        <li class="px-4 pt-4 pb-2"><small class="nav-text">Account</small></li>

        <li>
            <a class="nav-link <?= (uri_string() == 'recharge') ? 'active' : '' ?>" href="<?= site_url('recharge') ?>">
                <i class="ti ti-wallet"></i>
                <span class="nav-text">Recharge</span>
            </a>
        </li>

        <li>
            <a class="nav-link <?= (uri_string() == 'settings') ? 'active' : '' ?>" href="<?= site_url('settings') ?>">
                <i class="ti ti-settings"></i>
                <span class="nav-text">Settings</span>
            </a>
        </li>

        <li>
            <a class="nav-link" href="<?= site_url('logout') ?>">
                <i class="ti ti-logout"></i>
                <span class="nav-text">Logout</span>
            </a>
        </li>
    </ul>
</aside>
