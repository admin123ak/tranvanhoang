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
                <i class="ti ti-layout-dashboard"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>

        <li class="has-sub">
            <a class="nav-link <?= (strpos(uri_string(), 'keys') !== false) ? 'active' : '' ?>" href="javascript:void(0)">
                <i class="ti ti-key"></i>
                <span class="nav-text">Keys</span>
                <i class="ti ti-chevron-down ms-auto submenu-arrow"></i>
            </a>
            <ul class="submenu <?= (strpos(uri_string(), 'keys') !== false) ? 'active show' : '' ?>">
                <li>
                    <a class="nav-link <?= (uri_string() == 'keys') ? 'active' : '' ?>" href="<?= site_url('keys') ?>">
                        <span class="nav-text">List Keys</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link <?= (uri_string() == 'keys/generate') ? 'active' : '' ?>" href="<?= site_url('keys/generate') ?>">
                        <span class="nav-text">Generate</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link <?= (strpos(uri_string(), 'keys/devices') !== false) ? 'active' : '' ?>" href="<?= site_url('keys/devices') ?>">
                        <span class="nav-text">Devices</span>
                    </a>
                </li>
            </ul>
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
            <a class="nav-link <?= (strpos(uri_string(), 'admin/getkey-config') !== false) ? 'active' : '' ?>" href="<?= site_url('admin/getkey-config') ?>">
                <i class="ti ti-link"></i>
                <span class="nav-text">GetKey Config</span>
            </a>
        </li>

        <li class="has-sub">
            <a class="nav-link <?= (strpos(uri_string(), 'admin') !== false && strpos(uri_string(), 'packages') === false && strpos(uri_string(), 'getkey-config') === false) ? 'active' : '' ?>" href="javascript:void(0)">
                <i class="ti ti-shield-lock"></i>
                <span class="nav-text">Admin</span>
                <i class="ti ti-chevron-down ms-auto submenu-arrow"></i>
            </a>
            <ul class="submenu <?= (strpos(uri_string(), 'admin') !== false && strpos(uri_string(), 'packages') === false && strpos(uri_string(), 'getkey-config') === false) ? 'active show' : '' ?>">
                <li>
                    <a class="nav-link <?= (uri_string() == 'admin/manage-users') ? 'active' : '' ?>" href="<?= site_url('admin/manage-users') ?>">
                        <span class="nav-text">Manage Users</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link <?= (uri_string() == 'admin/create-referral') ? 'active' : '' ?>" href="<?= site_url('admin/create-referral') ?>">
                        <span class="nav-text">Create Referral</span>
                    </a>
                </li>
            </ul>
        </li>
        <?php endif; ?>

        <li class="px-4 pt-4 pb-2"><small class="nav-text">Developer</small></li>

        <li>
            <a class="nav-link" href="<?= site_url('api-docs') ?>">
                <i class="ti ti-file-code"></i>
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

<style>
/* Sidebar submenu styles - override InApp defaults */
.sidebar .has-sub {
    position: relative;
}

.sidebar .submenu {
    display: none !important;
    padding-left: 1.5rem !important;
    list-style: none !important;
    margin: 0 !important;
}

.sidebar .submenu.open {
    display: block !important;
}

.sidebar .submenu .nav-link {
    padding: 6px 10px !important;
    font-size: 0.8rem !important;
    color: #737373 !important;
    margin: 1px 12px !important;
}

.sidebar .submenu .nav-link:hover,
.sidebar .submenu .nav-link.active {
    color: #e66239 !important;
    background-color: transparent !important;
}

.sidebar .submenu-arrow {
    transition: transform 0.3s;
    font-size: 14px !important;
    margin-left: auto !important;
}

.sidebar .has-sub.active .submenu-arrow {
    transform: rotate(180deg);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hasSubItems = document.querySelectorAll('.has-sub > .nav-link');

    hasSubItems.forEach(function(item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.closest('.has-sub');
            const submenu = parent.querySelector('.submenu');

            // Close other open submenus
            document.querySelectorAll('.has-sub').forEach(function(sub) {
                if (sub !== parent) {
                    const otherSubmenu = sub.querySelector('.submenu');
                    if (otherSubmenu) {
                        otherSubmenu.classList.remove('open');
                    }
                    sub.classList.remove('active');
                }
            });

            // Toggle current submenu
            if (submenu) {
                submenu.classList.toggle('open');
            }
            parent.classList.toggle('active');
        });
    });

    // Auto-open active submenu on page load
    const activeSubmenu = document.querySelector('.submenu.active');
    if (activeSubmenu) {
        activeSubmenu.classList.add('open');
        const parent = activeSubmenu.closest('.has-sub');
        if (parent) {
            parent.classList.add('active');
            const arrow = parent.querySelector('.submenu-arrow');
            if (arrow) {
                arrow.style.transform = 'rotate(180deg)';
            }
        }
    }
});
</script>
