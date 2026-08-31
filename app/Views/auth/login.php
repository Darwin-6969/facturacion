<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Iniciar Sesión | Sistema de Facturación</title>

    <!-- Fuente -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet"
          href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">

    <!-- CSS personalizado -->
    <link rel="stylesheet"
          href="<?= base_url('assets/css/custom.css') ?>">

</head>
<body class="login-page-custom">





    <div class="login-container">


        <!-- =========================================
             MARCA / LOGO
             ========================================= -->

        <div class="login-brand">

            <div class="login-brand-icon">

                <img
                    src="<?= base_url('assets/img/logo.png') ?>"
                    alt="Logo Facturación App"
                    style="
                        width: 58px;
                        height: 58px;
                        object-fit: contain;
                    "
                >

            </div>


            <h1>
                Facturación App
            </h1>

            <p>
                Sistema de gestión de facturación
            </p>

        </div>


        <!-- =========================================
             CARD LOGIN
             ========================================= -->

        <div class="card login-card border-0">

            <div class="card-body">


                <!-- Encabezado -->

                <div class="text-center mb-4">

                    <h2 class="login-title">
                        Bienvenido
                    </h2>

                    <p class="login-subtitle mb-0">
                        Ingresa tus credenciales para continuar
                    </p>

                </div>


                <!-- =========================================
                     MENSAJE DE ERROR
                     ========================================= -->

                <?php if (session()->getFlashdata('error')): ?>

                    <div
                        class="alert alert-danger alert-dismissible fade show border-0 rounded-3"
                        role="alert"
                    >

                        <i class="bi bi-exclamation-triangle-fill me-2"></i>

                        <?= session()->getFlashdata('error') ?>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Cerrar"
                        ></button>

                    </div>

                <?php endif; ?>


                <!-- =========================================
                     FORMULARIO
                     ========================================= -->

                <form
                    action="<?= base_url('login/authenticate') ?>"
                    method="post"
                    autocomplete="off"
                >

                    <?= csrf_field() ?>


                    <!-- USUARIO -->

                    <div class="mb-3">

                        <label
                            for="username"
                            class="form-label fw-semibold"
                        >
                            Usuario
                        </label>

                        <div class="input-group login-input-group">

                            <span class="input-group-text">

                                <i class="bi bi-person"></i>

                            </span>

                            <input
                                type="text"
                                name="username"
                                id="username"
                                class="form-control login-input"
                                placeholder="Ingresa tu usuario"
                                required
                                autofocus
                            >

                        </div>

                    </div>


                    <!-- CONTRASEÑA -->

                    <div class="mb-4">

                        <label
                            for="password"
                            class="form-label fw-semibold"
                        >
                            Contraseña
                        </label>

                        <div class="input-group login-input-group">

                            <span class="input-group-text">

                                <i class="bi bi-lock"></i>

                            </span>


                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control login-input"
                                placeholder="Ingresa tu contraseña"
                                required
                            >


                            <button
                                type="button"
                                class="btn btn-light border"
                                id="togglePassword"
                                title="Mostrar contraseña"
                            >

                                <i
                                    class="bi bi-eye"
                                    id="passwordIcon"
                                ></i>

                            </button>

                        </div>

                    </div>


                    <!-- BOTÓN -->

                    <div class="d-grid">

                        <button
                            type="submit"
                            class="btn login-btn text-white"
                        >

                            <i class="bi bi-box-arrow-in-right me-2"></i>

                            Ingresar al sistema

                        </button>

                    </div>

                </form>

            </div>

        </div>


        <!-- =========================================
             FOOTER
             ========================================= -->

        <div class="text-center mt-4">

            <small class="text-white-50">

                &copy; <?= date('Y') ?>

                Sistema de Facturación

                <span class="mx-1">•</span>

                Todos los derechos reservados

            </small>

        </div>


    </div>


    <!-- Bootstrap -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


    <!-- =========================================
         MOSTRAR / OCULTAR CONTRASEÑA
         ========================================= -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const password =
                document.getElementById('password');

            const toggle =
                document.getElementById('togglePassword');

            const icon =
                document.getElementById('passwordIcon');


            if (password && toggle && icon) {

                toggle.addEventListener('click', function () {

                    if (password.type === 'password') {

                        password.type = 'text';

                        icon.classList.remove('bi-eye');

                        icon.classList.add('bi-eye-slash');

                        toggle.setAttribute(
                            'title',
                            'Ocultar contraseña'
                        );

                    } else {

                        password.type = 'password';

                        icon.classList.remove('bi-eye-slash');

                        icon.classList.add('bi-eye');

                        toggle.setAttribute(
                            'title',
                            'Mostrar contraseña'
                        );

                    }

                });

            }

        });

    </script>


</body>

</html>
