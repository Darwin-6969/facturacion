<aside class="app-sidebar shadow" data-bs-theme="dark">

    <div class="sidebar-brand">
        <a href="<?= base_url('dashboard') ?>" class="brand-link d-flex align-items-center">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Facturación" style="width: 40px; height: 40px; object-fit: contain; margin-right: 10px;">
            <span class="brand-text fw-semibold">Facturación App</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <!-- SOLO ADMINISTRADOR -->
                <?php if (session()->get('rol') === 'administrador'): ?>
                    <li class="nav-item">
                        <a href="<?= base_url('dashboard') ?>" class="nav-link <?= url_is('dashboard') || url_is('/') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-grid-1x2-fill"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('clientes') ?>" class="nav-link <?= url_is('clientes*') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>Clientes</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('categorias') ?>" class="nav-link <?= url_is('categorias*') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-tags-fill"></i>
                            <p>Categorías</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('marcas') ?>" class="nav-link <?= url_is('marcas*') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-bookmark-check-fill"></i>
                            <p>Marcas</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('proveedores') ?>" class="nav-link <?= url_is('proveedores*') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-truck"></i>
                            <p>Proveedores</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('productos') ?>" class="nav-link <?= url_is('productos*') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-boxes"></i>
                            <p>Productos</p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- COMPARTIDO (ADMINISTRADOR Y ENCARGADO) -->
                <li class="nav-item <?= url_is('facturas*') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= url_is('facturas*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-receipt-cutoff"></i>
                        <p>
                            Facturación
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('facturas/nueva') ?>" class="nav-link <?= url_is('facturas/nueva') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-plus-circle"></i>
                                <p>Nueva Factura</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('facturas') ?>" class="nav-link <?= (url_is('facturas') && !url_is('facturas/nueva')) ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-clock-history"></i>
                                <p>Historial</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Módulo de Compras (Exclusivo Administrador) -->
                    <li class="nav-item <?= (url_is('compras*')) ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link <?= (url_is('compras*')) ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>
                                Compras
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('compras') ?>" class="nav-link <?= (url_is('compras') && !url_is('compras/nueva')) ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Historial de Compras</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('compras/nueva') ?>" class="nav-link <?= (url_is('compras/nueva')) ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Nueva Compra / Stock</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                <!-- SOLO ADMINISTRADOR -->
                <?php if (session()->get('rol') === 'administrador'): ?>
                    <li class="nav-item">
                        <a href="<?= base_url('usuarios') ?>" class="nav-link <?= url_is('usuarios*') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-person-gear"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>
    </div>

</aside>