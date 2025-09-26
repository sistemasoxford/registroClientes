<?php 
    session_start();
    require_once BASE_PATH . 'config/config.php';
    require_once BASE_PATH . 'config/autoload.php';
    require_once BASE_PATH . 'config/rutas.php';
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
    </style>
</head>
<body class="app-default d-flex flex-column">

    <!--begin::App-->
    <div class="d-flex flex-column  align-items-center justify-content-between">

        <!-- Logo superior -->
        <div class="text-gray-500 text-center fw-semibold fs-6 pe-0 logo-top mt-20 mb-20">
            <img alt="Logo" src="<?php echo BASE_URL; ?>public/images/oxfdord.png" class="img-fluid" width="200" height="420" >
        </div>

        <!-- Formulario centrado más abajo -->
        <div class="w-100 w-md-100 w-lg-400px p-4 p-lg-10 mt-20 mb-20">
            <form class="form w-100 row mt-20 mb-20" id="clienteForm">

                <!-- Documento -->
               <div class="fv-row mb-5 col-12 pe-0 text-center">
                    <label class="form-label d-block">Documento</label>
                    <input type="text" 
                        name="PassportNumber" 
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
    </script>
</body>
</html>
