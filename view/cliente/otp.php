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
    <link href="<?php echo BASE_URL; ?>vendor/plugins/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
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

                                          <!-- Documento -->
                                          <div class="fv-row mb-5 col-md-12 pe-0">
                                              <label class="mb-2">OTP</label>
                                              <input type="text" id="otp" name="otp" autocomplete="off" class="form-control bg-transparent" />
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
    <!-- <script src="<?php echo BASE_URL; ?>vendor/plugins/datatables/datatables.bundle.js"></script> -->
    <!-- <script src="<?php echo BASE_URL; ?>vendor/signature_pad/signature_pad.umd.min.js"></script> -->
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="<?php echo BASE_URL; ?>view/cliente/js/otp.js"></script>
    <!--end::Custom Javascript-->
    <script>
        let urlOtp = "<?php echo BASE_URL; ?>cliente/php/otp";
        let urlEliminarSesion = "<?php echo BASE_URL; ?>";
        let urlRegistrar = "<?php echo BASE_URL; ?>cliente/registro";
    </script>
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>