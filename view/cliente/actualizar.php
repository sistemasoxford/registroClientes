<?php 
    session_start();
    require_once BASE_PATH . 'config/config.php';
    require_once BASE_PATH . 'config/autoload.php';
    require_once BASE_PATH . 'config/rutas.php';

    //Si no hay un session activa, redirigir a index
    if (!isset($_SESSION['usuario']['PassportNumber'])) {
        header('Location: ' . BASE_URL);
        exit;
    }

    $_SESSION['urlOtp'] = 0;
?>

<!DOCTYPE html>
<html lang="es">
<!--begin::Head-->

<head>
    <base href="../../../" />
    <title>Registro - OXFORD</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="<?php echo BASE_URL; ?>public/images/index/logoOxfordIcon.png" type="image/x-icon">
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="<?php echo BASE_URL; ?>plugins/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="<?php echo BASE_URL; ?>plugins/css/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL; ?>view/css/index.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-sidebar-minimize="on" class="app-default">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
            
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            <!--end::Header-->
            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <!--begin::Sidebar-->
                <!--end::Sidebar-->
                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <!--begin::Content container-->
							<div id="kt_app_content_container" class="app-container container-xxl">
                                <!--begin::Form-->
                                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                                    <!--begin::Wrapper-->
                                    <div class="w-lg-500px p-10">
                                        <!--begin::Form-->
                                        <form class="form w-100 row" id="clienteForm">
                                            <div class="text-center mb-11 pe-0">
                                                <h1 class="text-dark mb-3" style="color: #000000 !important;font-family: Poppins;">
                                                    Registro de Cliente
                                                </h1>
                                            </div>

                                            <div class="fv-row mb-5 col-md-12 pe-0">
                                                <label class="mb-2 required">Tipo de Documento</label>
                                                <select id="tDocumento" class="form-select bg-transparent" data-control="select2" data-placeholder="Seleccione el tipo de documento" data-hide-search="false" name="tDocumento">
                                                    <option value=""></option>
                                                    <option value="cc">Cédula de Ciudadanía</option>
                                                    <option value="ce">Cédula de Extranjería</option>
                                                    <option value="nit">NIT</option>
                                                    <option value="p">Pasaporte</option>
                                                    <option value="TI">Tarjeta de Identidad</option>
                                                </select>
                                            </div>

                                            <!-- Documento -->
                                            <div class="fv-row mb-5 col-md-12 pe-0">
                                                <label class="mb-2 required">Documento</label>
                                                <input type="text" name="PassportNumber" autocomplete="off" class="form-control bg-transparent" value="<?php echo $_SESSION["usuario"]["PassportNumber"] ?? null; ?>"/>
                                            </div>

                                            <!-- Nombre -->
                                            <div class="fv-row mb-5 col-md-6 pe-0">
                                                <label class="mb-2 required">Primer Nombre</label>
                                                <input type="text" name="FirstName" autocomplete="off" class="form-control bg-transparent" value="<?php echo $_SESSION["usuario"]["FirstName"] ?? null; ?>"/>
                                            </div>

                                            <!-- Apellido -->
                                            <div class="fv-row mb-5 col-md-6 pe-0">
                                                <label class="mb-2 required">Primer Apellido</label>
                                                <input type="text" name="LastName" autocomplete="off" class="form-control bg-transparent" value="<?php echo $_SESSION["usuario"]["LastName"] ?? null; ?>"/>
                                            </div>

                                            <!-- Sexo -->
                                            <div class="fv-row mb-5 col-md-12 pe-0">
                                                <label class="mb-2">Genero</label>
                                                <select id="Sex" class="form-select bg-transparent" data-control="select2" data-placeholder="Seleccione una ciudad" data-hide-search="true" name="status">
                                                    <option value="M" <?php echo (($_SESSION["usuario"]["Sex"] ?? '') == 'M') ? 'selected' : ''; ?>>Masculino</option>
                                                    <option value="F" <?php echo (($_SESSION["usuario"]["Sex"] ?? '') == 'F') ? 'selected' : ''; ?>>Femenino</option>
                                                </select>
                                            </div>

                                            <!-- Fecha nacimiento -->
                                            <div class="fv-row mb-5 col-md-12">
                                                <label class="mb-3 fw-bold d-block">Fecha de Nacimiento</label>
                                                <div class="row gx-3">
                                                    <div class="fv-row mb-5 col-md-4">
                                                        <label class="mb-2">Día</label>
                                                        <input type="number" placeholder="Día" name="BirthDateDay" class="form-control bg-transparent" value="<?php echo $_SESSION["usuario"]["BirthDateDay"] ?? null; ?>"/>
                                                    </div>
                                                    
                                                    <div class="fv-row mb-5 col-md-4">
                                                        <label class="mb-2">Mes</label>
                                                        <select id="BirthDateMonth" class="form-select bg-transparent" data-control="select2" data-placeholder="Mes" data-hide-search="false" name="BirthDateMonth">
                                                            <option value=""></option>
                                                            <option value="1" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '1') ? 'selected' : ''; ?>>Enero</option>
                                                            <option value="2" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '2') ? 'selected' : ''; ?>>Febrero</option>
                                                            <option value="3" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '3') ? 'selected' : ''; ?>>Marzo</option>
                                                            <option value="4" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '4') ? 'selected' : ''; ?>>Abril</option>
                                                            <option value="5" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '5') ? 'selected' : ''; ?>>Mayo</option>
                                                            <option value="6" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '6') ? 'selected' : ''; ?>>Junio</option>
                                                            <option value="7" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '7') ? 'selected' : ''; ?>>Julio</option>
                                                            <option value="8" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '8') ? 'selected' : ''; ?>>Agosto</option>
                                                            <option value="9" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '9') ? 'selected' : ''; ?>>Septiembre</option>
                                                            <option value="10" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '10') ? 'selected' : ''; ?>>Octubre</option>
                                                            <option value="11" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '11') ? 'selected' : ''; ?>>Noviembre</option>
                                                            <option value="12" <?php echo (($_SESSION["usuario"]["BirthDateMonth"] ?? '') == '12') ? 'selected' : ''; ?>>Diciembre</option>
                                                        </select>
                                                    </div>

                                                    <div class="fv-row mb-5 col-md-4">
                                                        <label class="mb-2">Año</label>
                                                        <input type="number" name="BirthDateYear" placeholder="Año" class="form-control bg-transparent" value="<?php echo $_SESSION["usuario"]["BirthDateYear"] ?? null; ?>"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="fv-row mb-5 col-md-12 pe-0">
                                                <label class="mb-2 required">Correo</label>
                                                <input type="email" name="Email" autocomplete="off" class="form-control bg-transparent" value="<?php echo $_SESSION["usuario"]["Email"] ?? null; ?>"/>
                                            </div>

                                            <!-- Celular -->
                                            <div class="fv-row mb-5 col-md-12 pe-0">
                                                <label class="mb-2 required">Celular</label>
                                                <input type="text" name="CellularPhoneNumber" autocomplete="off" class="form-control bg-transparent" value="<?php echo $_SESSION["usuario"]["CellularPhoneNumber"] ?? null; ?>"/>
                                            </div>

                                            <!-- Dirección -->
                                            <!-- <div class="fv-row mb-5 col-md-12 pe-0">
                                                <label class="mb-2 required">Dirección</label>
                                                <input type="text" name="AddressLine1" autocomplete="off" class="form-control bg-transparent" value="<?php echo $_SESSION["usuario"]["AddressLine1"] ?? null; ?>"/>
                                            </div> -->

                                            <!-- Departamento -->
                                            <div class="fv-row mb-5 col-md-12 pe-0">
                                                <label class="mb-2 required">Departamento</label>
                                                <select id="RegionId" class="form-select bg-transparent" data-control="select2" data-placeholder="Seleccione un departamento" data-hide-search="false" name="status">
                                                    <option value=""></option>
                                                </select>
                                            </div>

                                            <!-- Ciudad -->
                                            <div class="fv-row mb-5 col-md-12 pe-0">
                                                <label class="mb-2 required">Ciudad</label>
                                                <select id="City" class="form-select bg-transparent" data-control="select2" data-placeholder="Seleccione una ciudad" data-hide-search="false" name="status">
                                                    <option value=""></option>
                                                </select>
                                            </div>

                                            <!-- Aceptar términos -->
                                            <div class="fv-row mb-8 col-md-12 pe-0">
                                                <label class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="TextValue" value="d" />
                                                    <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">
                                                        Acepto los <a href="<?php echo BASE_URL; ?>aceptarTerminos/condiciones1" target="_blank" class="link-primary">términos y condiciones</a>
                                                    </span>
                                                </label>
                                            </div>

                                            <!-- Botón -->
                                            <div class="d-grid mb-5 pe-0">
                                                <button type="submit" id="kt_sign_up_submit" class="btn btn-dark" style="background-color: #000000;">
                                                    <span class="indicator-label">Enviar</span>
                                                    <span class="indicator-progress">Por favor espere...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </span>
                                                </button>
                                            </div>

                                            <div class="text-gray-500 text-center fw-semibold fs-6 pe-0">
                                              <img alt="Logo" src="<?php echo BASE_URL; ?>public/images/oxfdord.png" class="h-90px h-lg-75px">
                                            </div>
                                        </form>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Content wrapper-->
                    <!--begin::Footer-->
                    <!--end::Footer-->
                </div>
                <!--end:::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>
    <!--end::Scrolltop-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="<?php echo BASE_URL; ?>plugins/js/plugins.bundle.js"></script>
    <script src="<?php echo BASE_URL; ?>plugins/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="<?php echo BASE_URL; ?>plugins/datatables/datatables.bundle.js"></script>
    <!-- <script src="<?php echo BASE_URL; ?>vendor/signature_pad/signature_pad.umd.min.js"></script> -->
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="<?php echo BASE_URL; ?>view/cliente/js/ciudadesDepa.js"></script>
    <script src="<?php echo BASE_URL; ?>view/cliente/js/registro.js"></script>
    <!--end::Custom Javascript-->
    <script>
        let registrar = "<?php echo $_SESSION['urlOtp'] ?? 0; ?>";; // 1 para registrar, 0 para actualizar
        let urlActualizar = "<?php echo BASE_URL; ?>cliente/php/registro";
        let userDepto = "<?php echo $_SESSION['usuario']['RegionId'] ?? ''; ?>";
        let userCity  = "<?php echo $_SESSION['usuario']['City'] ?? ''; ?>";
        let urlCiudadesDepa = "<?php echo BASE_URL; ?>view/cliente/json/ciudadesDepa.json";
        let urlOtp = "<?php echo BASE_URL; ?>cliente/otp";
    </script>
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>