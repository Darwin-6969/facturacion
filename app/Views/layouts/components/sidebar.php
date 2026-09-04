<aside class="app-sidebar shadow"
       data-bs-theme="dark">

    <!-- Marca -->
    <div class="sidebar-brand">

    <a href="<?= base_url('facturacion') ?>"
       class="brand-link d-flex align-items-center">

        <img
            src="<?= base_url('assets/img/logo.png') ?>"
            alt="Logo Facturación"
            style="
                width: 40px;
                height: 40px;
                object-fit: contain;
                margin-right: 10px;
            "
        >

        <span class="brand-text fw-semibold">
            Facturación App
        </span>

    </a>

</div>


    <!-- Menú -->
    <div class="sidebar-wrapper">

        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">

                    <a href="<?= base_url('facturacion') ?>"
                       class="nav-link <?= url_is('facturacion') ? 'active' : '' ?>">

                        <i class="nav-icon bi bi-grid-1x2-fill"></i>

                        <p>
                            Dashboard
                        </p>

                    </a>

                </li>

                <!-- Clientes -->
                <li class="nav-item">
                    <a href="<?= base_url('clientes') ?>" class="nav-link <?= url_is('clientes*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Clientes</p>
                    </a>
                </li>

                <!-- Marcas -->
                <li class="nav-item">
                    <a href="<?= base_url('marcas') ?>" class="nav-link <?= url_is('marcas*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-bookmark-check-fill"></i>
                        <p>Marcas</p>
                    </a>
                </li>


                <!-- Facturación -->
                <li class="nav-item <?= url_is('facturas*') ? 'menu-open' : '' ?>">

                    <a href="#"
                       class="nav-link <?= url_is('facturas*') ? 'active' : '' ?>">

                        <i class="nav-icon bi bi-receipt-cutoff"></i>

                        <p>
                            Facturación

                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>

                    <!-- Categorías -->
                    <li class="nav-item">
                        <a href="<?= base_url('categorias') ?>" class="nav-link <?= url_is('categorias*') ? 'active' : '' ?>">
                            <i class="nav-icon bi bi-tags-fill"></i>
                            <p>Categorías</p>
                        </a>
                    </li>

                    <a class="nav-link" href="<?= base_url('proveedores') ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-truck"></i></div>
                        Proveedores
                    </a>

                    <li class="nav-item">
                        <a href="<?= base_url('usuarios') ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>


                    <ul class="nav nav-treeview">

                        <!-- Nueva factura -->
                        <li class="nav-item">

                            <a href="<?= base_url('facturas/nueva') ?>"
                               class="nav-link <?= url_is('facturas/nueva') ? 'active' : '' ?>">

                                <i class="nav-icon bi bi-plus-circle"></i>

                                <p>
                                    Nueva Factura
                                </p>

                            </a>

                        </li>


                        <!-- Historial -->
                        <li class="nav-item">

                            <a href="<?= base_url('facturas') ?>"
                               class="nav-link <?= url_is('facturas') ? 'active' : '' ?>">

                                <i class="nav-icon bi bi-clock-history"></i>

                                <p>
                                    Historial
                                </p>

                            </a>

                        </li>

                    </ul>

                </li>

            </ul>

        </nav>

    </div>

</aside>
