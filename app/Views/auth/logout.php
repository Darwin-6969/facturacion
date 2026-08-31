<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Sesión cerrada | Sistema de Facturación
    </title>


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


<body class="logout-page">


    <div class="logout-card">


        <!-- =================================================
             LOGO
             ================================================= -->

        <div class="mb-4">

            <img
                src="<?= base_url('assets/img/logo.png') ?>"
                alt="Logo Facturación App"
                style="
                    width: 90px;
                    height: 90px;
                    object-fit: contain;
                "
            >

        </div>


        <!-- =================================================
             ICONO DE CONFIRMACIÓN
             ================================================= -->

        <div class="logout-icon">

            <i class="bi bi-check-lg"></i>

        </div>


        <!-- =================================================
             TÍTULO
             ================================================= -->

        <h1>
            Sesión cerrada
        </h1>


        <!-- =================================================
             MENSAJE
             ================================================= -->

        <p class="mb-4">

            Has cerrado sesión correctamente.

            <br>

            Gracias por utilizar el Sistema de Facturación.

        </p>


        <!-- =================================================
             BOTÓN
             ================================================= -->

        <a
            href="<?= base_url('login') ?>"
            class="logout-btn w-100"
        >

            <i class="bi bi-box-arrow-in-right"></i>

            Volver a iniciar sesión

        </a>


        <!-- =================================================
             FOOTER
             ================================================= -->

        <div class="mt-4">

            <small class="text-muted">

                &copy; <?= date('Y') ?>

                Sistema de Facturación

                <span class="mx-1">•</span>

                Todos los derechos reservados

            </small>

        </div>


    </div>


</body>

</html>
