<?php 
    session_start();
    require_once BASE_PATH . 'config/config.php';
    require_once BASE_PATH . 'config/autoload.php';
    require_once BASE_PATH . 'config/rutas.php';

    if (!isset($_SESSION['cliente']) || !isset($_SESSION['cliente']['documento']) || !isset($_SESSION['usuario']['PassportNumber'])) {
        header('Location: ' . BASE_URL);
        exit;
    }
    echo BASE_URL . "cliente/pasoFinal";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <base href="../../../" />
    <title>Registro - OXFORD</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>public/images/index/logoOxfordIcon.png" type="image/x-icon">

    <!-- Fuentes -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />

    <!-- Vendor -->
    <link href="<?php echo BASE_URL; ?>vendor/plugins/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

    <!-- Estilos Globales -->
    <link href="<?php echo BASE_URL; ?>plugins/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>view/css/index.css" rel="stylesheet" type="text/css" />

    <script>
        // Protección contra clickjacking
        if (window.top !== window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
    <style>
        .instructions {
            font-size: 17px;
            font-weight: 400;
            margin-bottom: 35px; /* espacio con el texto de instagram */
            line-height: 1.6;
        }
        .instructions strong {
            font-weight: 700;
        }
    </style>
</head>

<body class="app-default d-flex flex-column">

    <!-- Contenedor principal -->
    <div class="d-flex flex-column flex-grow-1 align-items-center justify-content-between">

        <!-- Logo superior -->
        <div class="logo-top text-center mt-20 mb-10">
            <img alt="Logo Superior" src="<?php echo BASE_URL; ?>public/images/oxfdord.png" 
                 class="img-fluid" width="200" height="420">
        </div>

        <!-- Formulario -->
        <div class="w-100 w-md-100 w-lg-400px p-4 p-lg-10 mt-5 mb-5">
            <form class="form w-100 row" id="clienteForm">
                

                <!-- Campo OTP -->
                <div class="fv-row mb-5 col-12 pe-0 text-center">
                    <label class="instructions form-label d-block">Ingresa el código de verificación que llegará a tu celular o correo</label>
                    <input type="tel" 
                        id="otp"
                        name="otp" 
                        autocomplete="off" 
                        class="form-control bg-transparent mx-auto" 
                        style="max-width: 250px;" 
                        maxlength="6"/>
                </div>

                
                <!-- Reenviar OTP -->
                <div class="fv-row mb-8 col-md-12 text-center">
                    <span class="fw-semibold text-gray-700 fs-base">
                        ¿No recibiste tu código? 
                        <a href="#" id="reenviarOtp" class="link-primary disabled" 
                           style="pointer-events: none; opacity: 0.6;">
                            Reenviar OTP en <span id="contador">05:00</span>
                        </a>
                    </span>
                </div>

                <!-- Botón -->
                <div class="d-grid mb-5">
                    <button type="submit" id="kt_sign_up_submit" 
                            class="btn btn-dark mx-auto w-100" 
                            style="background-color: #000000; max-width: 250px;">
                        <span class="indicator-label">Enviar</span>
                        <span class="indicator-progress">Por favor espere...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Logo inferior -->
        <div class="logo-bottom text-center mb-20 mt-10">
            <img alt="Logo Inferior" src="<?php echo BASE_URL; ?>public/images/away.png" 
                 class="img-fluid" width="150" height="150">
        </div>
    </div>

    <!-- Scrolltop -->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>

    <!-- Scripts -->
    <script src="<?php echo BASE_URL; ?>plugins/js/plugins.bundle.js"></script>
    <script src="<?php echo BASE_URL; ?>plugins/js/scripts.bundle.js"></script>
    <script src="<?php echo BASE_URL; ?>view/cliente/js/otp.js"></script>
    <script src="<?php echo BASE_URL; ?>view/cliente/js/contadorOtp.js"></script>

    <script>
        let urlOtp = "<?php echo BASE_URL; ?>cliente/php/otp";
        let urlReenviarOtp = "<?php echo BASE_URL; ?>cliente/php/reenviarOtp";
        let urlEliminarSesion = "<?php echo BASE_URL; ?>";
        let urlRegistrar = "<?php echo BASE_URL; ?>cliente/registro";
        let urlPasoFinal = "<?php echo BASE_URL; ?>cliente/pasoFinal";
    </script>
</body>
</html>
