<nav class="app-header navbar navbar-expand bg-body">

    <div class="container-fluid">

        <!-- Menú izquierdo -->
        <ul class="navbar-nav">

            <li class="nav-item">

                <a class="nav-link"
                   data-lte-toggle="sidebar"
                   href="#"
                   role="button"
                   title="Abrir menú">

                    <i class="bi bi-list fs-5"></i>

                </a>

            </li>

            <li class="nav-item d-none d-md-block">

                <a href="<?= base_url('dashboard') ?>"
                   class="nav-link fw-semibold">

                    <i class="bi bi-house-door me-1"></i>
                    Inicio

                </a>

            </li>

        </ul>


        <!-- Menú derecho -->
        <ul class="navbar-nav ms-auto">

            <!-- Usuario -->
            <li class="nav-item dropdown user-menu">

                <a href="#"
                   class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                   data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle fs-4 text-primary"></i>

                    <span class="d-none d-md-inline fw-semibold text-secondary text-capitalize">

                        <?= esc(session()->get('nombre') ?? 'Usuario') ?>

                    </span>

                </a>


                <!-- Dropdown -->
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow border-0 mt-2">

                    <!-- Cabecera -->
                    <li class="user-header p-4 text-center border-bottom">

                        <div class="mb-2">

                            <i class="bi bi-person-circle fs-1 text-primary"></i>

                        </div>

                        <p class="mb-1 fw-bold text-capitalize">

                            <?= esc(session()->get('nombre') ?? 'Usuario') ?>

                        </p>

                        <small class="text-muted d-block mb-2">

                            <?= esc(session()->get('correo') ?? '') ?>

                        </small>

                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 text-capitalize">

                            <?= esc(session()->get('rol') ?? 'encargado') ?>

                        </span>

                    </li>


                    <!-- Perfil -->
                    <li>

                        <a href="<?= base_url('perfil') ?>"
                           class="dropdown-item py-3">

                            <i class="bi bi-person me-2 text-primary"></i>

                            Mi Perfil

                        </a>

                    </li>


                    <!-- Configuración -->
                    <li>

                        <a href="<?= base_url('configuracion') ?>"
                           class="dropdown-item py-3">

                            <i class="bi bi-gear me-2 text-secondary"></i>

                            Configuración

                        </a>

                    </li>


                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    <!-- Logout -->
                    <li class="p-2">

                        <a href="<?= base_url('logout') ?>"
                           class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">

                            <i class="bi bi-box-arrow-right"></i>

                            Cerrar Sesión

                        </a>

                    </li>

                </ul>

            </li>

        </ul>

    </div>

</nav>