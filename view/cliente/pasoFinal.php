<?php 
    session_start();
    require_once BASE_PATH . 'config/config.php';
    require_once BASE_PATH . 'config/autoload.php';
    require_once BASE_PATH . 'config/rutas.php';

    if (!isset($_SESSION['cliente']) || !isset($_SESSION['cliente']['documento']) || !isset($_SESSION['usuario']['PassportNumber'])) {
    header('Location: ' . BASE_URL);
    exit;
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="../../../" />
    <title>Registro - OXFORD</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>public/images/index/logoOxfordIcon.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

    <!-- Global Styles -->
    <link href="<?php echo BASE_URL; ?>plugins/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>view/css/index.css" rel="stylesheet" type="text/css" />

    <style>
        body {
            background: #fff;
            font-family: 'Inter', sans-serif;
        }
        .logo-top img {
            max-height: 100px;
        }
        .badge-step {
            background: #f5f5f5;
            border-radius: 20px;
            padding: 6px 20px;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 30px; /* espacio debajo del paso */
        }
        .instructions {
            font-size: 17px;
            font-weight: 400;
            margin-bottom: 35px; /* espacio con el texto de instagram */
            line-height: 1.6;
        }
        .instructions strong {
            font-weight: 700;
        }
        .form-label {
            font-size: 15px;
            font-weight: 400;
            margin-bottom: 12px;
            display: block;
        }
        .form-control {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 12px;
            font-size: 16px;
            text-align: center;
        }
        .btn-dark {
            background-color: #000 !important;
            border-radius: 8px;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 40px; /* aire antes del botón */
        }
        .footer-text {
            font-size: 12px;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 50px; /* espacio antes del footer */
        }
    </style>
</head>
<body class="app-default d-flex flex-column">

    <!--begin::App-->
    <div class="d-flex flex-column align-items-center justify-content-between min-vh-100 py-10">

        <!-- Logo superior -->
        <div class="logo-top mb-10 text-center">
            <img alt="Logo" src="<?php echo BASE_URL; ?>public/images/oxfdord.png" width="180">
        </div>

        <!-- Contenido central -->
        <div class="text-center w-100 px-4" style="max-width: 420px;">

            <!-- Paso #2 -->
            <span class="badge-step mt-5">Paso #2</span>

            <!-- Instrucciones -->
            <p class="instructions mt-15">
                Ahora, para terminar tu inscripción,<br>
                Síguenos en nuestras redes sociales <br>
                <strong>@OXFORDJEANS</strong>
            </p>

            <!-- Formulario -->
            <form id="clienteForm" class="form w-100">

                <!-- Input Instagram -->
                <div class="mb-4 mt-20">
                    <label class="form-label">
                        Déjanos tu usuario de instagram para encontrarte en motivo de que seas un ganador
                    </label>
                    <input type="text"
                           name="instagram_user"
                           placeholder="@TuUsuario"
                           autocomplete="off"
                           class="form-control mx-auto"
                           style="max-width: 280px;" />
                </div>

                <!-- Botón -->
                <button type="submit"
                        id="kt_sign_up_submit"
                        class="btn btn-dark w-100 mx-auto"
                        style="max-width: 280px;">
                    Finalizar
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-auto footer-text">
            A WAY TO LIVE
        </div>
    </div>
    <!--end::App-->

    <!-- Scripts -->
    <script src="<?php echo BASE_URL; ?>plugins/js/plugins.bundle.js"></script>
    <script src="<?php echo BASE_URL; ?>plugins/js/scripts.bundle.js"></script>
    <script src="<?php echo BASE_URL; ?>view/js/cliente.js"></script>

    <script>
        let urlCliente = "<?php echo BASE_URL; ?>php/cliente";
        let urlActualizar = "<?php echo BASE_URL; ?>cliente/actualizar";
        let urlEliminarSesion = "<?php echo BASE_URL; ?>";
        let urlRegistrar = "<?php echo BASE_URL; ?>cliente/registro";
    </script>
</body>
</html>
