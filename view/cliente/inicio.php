<?php 
    session_start();
    require_once BASE_PATH . 'config/config.php';
    require_once BASE_PATH . 'config/autoload.php';
    require_once BASE_PATH . 'config/rutas.php';

    // Obtener la URL completa
    $url = $_SERVER['REQUEST_URI'];

    // Usar una expresión regular para extraer el número de la tienda y el número de la pregunta
    if (preg_match('/\/(\d+)(?:\/(\d+))?\/?$/', $url, $matches)) {
        $tienda = $matches[1];

        if (!is_numeric($tienda)) {
            header("Location: " . BASE_URL . "404");
            exit();
        }
    } else {
        header("Location: " . BASE_URL . "404");
        exit();
    }

    $objTienda = new Tienda(null, $tienda);
    $objControlTienda = new ControlTienda($objTienda);
    $resultado = $objControlTienda->buscarTienda();
    if ($resultado['success']) {
        $_SESSION['cliente']['nombre_ciudad'] = $resultado['data']['nombre_ciudad'];
        $_SESSION['cliente']['codigo_tienda'] = $resultado['data']['codigo_tienda'];
        $_SESSION['cliente']['codigo_postal'] = $resultado['data']['codigo_postal'];
        $_SESSION['cliente']['codigo_departamento'] = $resultado['data']['codigo_departamento'];
    } else {
        // Manejar el error si la tienda no se encuentra
        die("Error: " . $resultado['message']);
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

    <!-- Vendor Styles -->
    <link href="<?php echo BASE_URL; ?>vendor/plugins/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

    <!-- Global Styles -->
    <link href="<?php echo BASE_URL; ?>plugins/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>view/css/index.css" rel="stylesheet" type="text/css" />

    <style>
        /* Ajuste de logos */
        .logo-top img {
            max-height: 90px;
        }
        @media (min-width: 992px) {
            .logo-top img {
                max-height: 150px;
            }
        }
        .logo-bottom img {
            max-height: 20px;
        }
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
    <div class="d-flex flex-column  align-items-center justify-content-between">

        <!-- Logo superior -->
        <div class="text-gray-500 text-center fw-semibold fs-6 pe-0 logo-top mt-20 mb-20">
            <img alt="Logo" src="<?php echo BASE_URL; ?>public/images/oxfdord.png" class="img-fluid" width="200" height="420" >
        </div>
        <span class="badge-step">Paso #1</span>

        <!-- Formulario centrado más abajo -->
        <div class="w-100 w-md-100 w-lg-400px p-4 p-lg-10 mt-20 mb-20">
            <form class="form w-100 row mt-20 mb-20" id="clienteForm">

                <!-- Documento -->
               <div class="fv-row mb-5 col-12 pe-0 text-center">
                    <label class="form-label d-block">Documento</label>
                    <input type="tel" 
                        name="PassportNumber" 
                        id="PassportNumber"
                        autocomplete="off" 
                        class="form-control bg-transparent mx-auto" 
                        style="max-width: 250px;" />
                </div>

                <!-- Botón -->
                <div class="d-grid mb-5 pe-0">
                    <button type="submit" 
                            id="kt_sign_up_submit" 
                            class="btn btn-dark mx-auto w-100" 
                            style="background-color: #000000; max-width: 250px;">
                        <span class="indicator-label">Siguiente</span>
                        <span class="indicator-progress">Por favor espere...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>

            </form>
        </div>

        <!-- Logo inferior más pegado abajo -->
        <div class="text-gray-500 text-center fw-semibold fs-6 pe-0 logo-bottom mb-0 mt-20">
            <img alt="Logo" src="<?php echo BASE_URL; ?>public/images/away.png" class="img-fluid" width="150" height="150">
        </div>

    </div>
    <!--end::App-->

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
    <script src="<?php echo BASE_URL; ?>view/js/cliente.js"></script>

    <script>
        let urlCliente = "<?php echo BASE_URL; ?>php/cliente";
        let urlActualizar = "<?php echo BASE_URL; ?>cliente/actualizar";
        let urlRegistrar = "<?php echo BASE_URL; ?>cliente/registro";
        let urlPasoFinal = "<?php echo BASE_URL; ?>cliente/pasoFinal"; 
        document.getElementById("PassportNumber").addEventListener("input", function() {
            // Permitir solo números
            this.value = this.value.replace(/[^0-9]/g, '');
        });       
    </script>
</body>
</html>
