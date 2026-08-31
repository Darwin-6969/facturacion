<?= $this->extend('layouts/main') ?>


<!-- =====================================================
     TÍTULO
     ===================================================== -->

<?= $this->section('title') ?>

Dashboard

<?= $this->endSection() ?>


<!-- =====================================================
     TÍTULO DE LA PÁGINA
     ===================================================== -->

<?= $this->section('page_title') ?>

Dashboard

<?= $this->endSection() ?>


<!-- =====================================================
     CONTENIDO
     ===================================================== -->

<?= $this->section('content') ?>

<div class="container-fluid">


    <!-- =================================================
         BIENVENIDA
         ================================================= -->

    <div class="dashboard-welcome">

        <div class="position-relative" style="z-index: 2;">

            <h2>
                ¡Bienvenido,
                <?= esc(session('name') ?? 'Administrador') ?>! 👋
            </h2>

            <p>
                Gestiona tus facturas y mantén el control
                de tu negocio desde un solo lugar.
            </p>

        </div>

    </div>


    <!-- =================================================
         ESTADÍSTICAS
         ================================================= -->

    <div class="row g-4 mb-4">


        <!-- Facturas -->
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card dashboard-stat h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="stat-title">
                                Facturas
                            </div>

                            <div class="stat-value">
                                Gestión
                            </div>

                        </div>

                        <div class="stat-icon primary">

                            <i class="bi bi-receipt-cutoff"></i>

                        </div>

                    </div>

                    <div class="mt-3 small text-muted">

                        <i class="bi bi-check-circle-fill text-success me-1"></i>

                        Módulo disponible

                    </div>

                </div>

            </div>

        </div>


        <!-- Nueva factura -->
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card dashboard-stat h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="stat-title">
                                Crear
                            </div>

                            <div class="stat-value">
                                Nueva factura
                            </div>

                        </div>

                        <div class="stat-icon success">

                            <i class="bi bi-plus-lg"></i>

                        </div>

                    </div>

                    <div class="mt-3 small text-muted">

                        Registra una nueva venta

                    </div>

                </div>

            </div>

        </div>


        <!-- Historial -->
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card dashboard-stat h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="stat-title">
                                Historial
                            </div>

                            <div class="stat-value">
                                Consultar
                            </div>

                        </div>

                        <div class="stat-icon warning">

                            <i class="bi bi-clock-history"></i>

                        </div>

                    </div>

                    <div class="mt-3 small text-muted">

                        Revisa tus documentos

                    </div>

                </div>

            </div>

        </div>


        <!-- Sistema -->
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card dashboard-stat h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="stat-title">
                                Sistema
                            </div>

                            <div class="stat-value">
                                Activo
                            </div>

                        </div>

                        <div class="stat-icon info">

                            <i class="bi bi-shield-check"></i>

                        </div>

                    </div>

                    <div class="mt-3 small text-success">

                        <i class="bi bi-circle-fill me-1"
                           style="font-size: 7px;"></i>

                        Sesión activa

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =================================================
         CONTENIDO INFERIOR
         ================================================= -->

    <div class="row g-4">


        <!-- Panel principal -->
        <div class="col-lg-8">

            <div class="card h-100">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h3 class="card-title mb-1">

                                <i class="bi bi-bar-chart-line me-2 text-primary"></i>

                                Panel de facturación

                            </h3>

                            <small class="text-muted">

                                Accede rápidamente a las principales funciones.

                            </small>

                        </div>

                        <span class="badge bg-success-subtle text-success rounded-pill">

                            Sistema activo

                        </span>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        <!-- Nueva factura -->
                        <div class="col-md-6">

                            <a href="<?= base_url('facturas/nueva') ?>"
                               class="quick-action">

                                <div class="quick-action-icon">

                                    <i class="bi bi-plus-lg"></i>

                                </div>

                                <div>

                                    <strong class="d-block">
                                        Nueva factura
                                    </strong>

                                    <small class="text-muted">
                                        Registrar una nueva factura
                                    </small>

                                </div>

                                <i class="bi bi-chevron-right ms-auto"></i>

                            </a>

                        </div>


                        <!-- Historial -->
                        <div class="col-md-6">

                            <a href="<?= base_url('facturas') ?>"
                               class="quick-action">

                                <div class="quick-action-icon">

                                    <i class="bi bi-receipt"></i>

                                </div>

                                <div>

                                    <strong class="d-block">
                                        Historial
                                    </strong>

                                    <small class="text-muted">
                                        Consultar facturas registradas
                                    </small>

                                </div>

                                <i class="bi bi-chevron-right ms-auto"></i>

                            </a>

                        </div>


                    </div>


                    <!-- Mensaje informativo -->

                    <div class="mt-4 p-4 rounded-4"
                         style="background: #f8fafc;">

                        <div class="d-flex align-items-start">

                            <div class="stat-icon primary me-3">

                                <i class="bi bi-lightbulb"></i>

                            </div>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    Consejo
                                </h6>

                                <p class="text-muted small mb-0">

                                    Utiliza el menú lateral para acceder
                                    a todas las funciones del sistema
                                    de facturación.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Panel usuario -->
        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header">

                    <h3 class="card-title">

                        <i class="bi bi-person-badge me-2 text-primary"></i>

                        Tu sesión

                    </h3>

                </div>

                <div class="card-body text-center">

                    <div class="mb-3">

                        <i class="bi bi-person-circle"
                           style="
                                font-size: 75px;
                                color: #6366f1;
                           ">
                        </i>

                    </div>

                    <h5 class="fw-bold mb-1">

                        <?= esc(session('name') ?? 'Administrador') ?>

                    </h5>

                    <p class="text-muted mb-3">

                        @<?= esc(session('username') ?? 'admin') ?>

                    </p>

                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">

                        <i class="bi bi-check-circle me-1"></i>

                        Sesión activa

                    </span>

                    <hr class="my-4">

                    <div class="text-start">

                        <small class="text-muted d-block mb-1">
                            Estado del sistema
                        </small>

                        <div class="d-flex align-items-center">

                            <span class="bg-success rounded-circle me-2"
                                  style="width: 9px; height: 9px;">
                            </span>

                            <strong>
                                Operativo
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>
