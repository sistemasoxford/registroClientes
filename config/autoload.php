<?php

// Función para buscar archivos en un directorio y sus subcarpetas
function searchInDirectories($directory, $className) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    foreach ($iterator as $file) {
        // Si el archivo coincide con la clase, se devuelve la ruta
        if ($file->getFilename() === $className . '.php') {
            return $file->getRealPath();
        }
    }
    return false;
}

// Registra el autoloader personalizado
spl_autoload_register(function ($class) {
    // Base directories for the namespaces
    $psr_base_dir             = BASE_PATH . '/vendor/psr/simple-cache/src/';
    $phpspreadsheet_base_dir  = BASE_PATH . '/vendor/PhpSpreadsheet/';
    $phpmailer_base_dir       = BASE_PATH . '/vendor/phpmailer/src/';
    $baconqrcode_base_dir     = BASE_PATH . '/vendor/BaconQrCode/src/';
    $dasprid_enum_base_dir    = BASE_PATH . '/vendor/Enum-master/src/';
    $tcpdf_base_dir           = BASE_PATH . '/vendor/TCPDF-main/'; 
    $endroid_qrcode_base_dir  = BASE_PATH . '/vendor/endroid/qr-code/src/';
    $controller               = BASE_PATH . '/controller/';
    $model                    = BASE_PATH . '/model/';

    // Handle psr/simple-cache classes
    $psr_prefix = 'Psr\\SimpleCache\\';
    $psr_len = strlen($psr_prefix);
    if (strncmp($psr_prefix, $class, $psr_len) === 0) {
        $relative_class = substr($class, $psr_len);
        $file = $psr_base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
        return;
    }

    // Handle PhpSpreadsheet classes
    $phpspreadsheet_prefix = 'PhpOffice\\PhpSpreadsheet\\';
    $phpspreadsheet_len = strlen($phpspreadsheet_prefix);
    if (strncmp($phpspreadsheet_prefix, $class, $phpspreadsheet_len) === 0) {
        $relative_class = substr($class, $phpspreadsheet_len);
        $file = $phpspreadsheet_base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
        return;
    }

    // Handle PHPMailer classes
    $phpmailer_prefix = 'PHPMailer\\PHPMailer\\';
    $phpmailer_len = strlen($phpmailer_prefix);
    if (strncmp($phpmailer_prefix, $class, $phpmailer_len) === 0) {
        $relative_class = substr($class, $phpmailer_len);
        $file = $phpmailer_base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
        return;
    }

    // Handle BaconQrCode classes
    $baconqrcode_prefix = 'BaconQrCode\\';
    $baconqrcode_len = strlen($baconqrcode_prefix);
    if (strncmp($baconqrcode_prefix, $class, $baconqrcode_len) === 0) {
        $relative_class = substr($class, $baconqrcode_len);
        $file = $baconqrcode_base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
        return;
    }

    // Handle dasprid/enum classes
    $dasprid_enum_prefix = 'DASPRiD\\Enum\\';
    $dasprid_enum_len = strlen($dasprid_enum_prefix);
    if (strncmp($dasprid_enum_prefix, $class, $dasprid_enum_len) === 0) {
        $relative_class = substr($class, $dasprid_enum_len);
        $file = $dasprid_enum_base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
        return;
    }

    // Handle Endroid/QrCode classes
    $endroid_prefix = 'Endroid\\QrCode\\';
    $endroid_len = strlen($endroid_prefix);
    if (strncmp($endroid_prefix, $class, $endroid_len) === 0) {
        $relative_class = substr($class, strlen('Endroid\\'));
        $file = $endroid_qrcode_base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
        return;
    }


    // Handle TCPDF (clase sin namespace)
    if ($class === 'TCPDF') {
        $file = $tcpdf_base_dir . 'tcpdf.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }

    // Handle custom classes in the controller directory (búsqueda recursiva)
    $controllerFile = searchInDirectories($controller, $class);
    if ($controllerFile) {
        require $controllerFile;
        return;
    }

    // Handle custom classes in the model directory (búsqueda recursiva)
    $modelFile = searchInDirectories($model, $class);
    if ($modelFile) {
        require $modelFile;
        return;
    }

    // Si no se encuentra la clase, registrar un error
    error_log("No se pudo cargar la clase: $class");
});
