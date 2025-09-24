<?php
// Iniciar sesión (si no está iniciada)
session_start();
require_once BASE_PATH . 'config/config.php';
require_once BASE_PATH . 'config/autoload.php';
require_once BASE_PATH . 'config/rutas.php';

// 1. Eliminar todas las variables de sesión
$_SESSION = array();

// 2. Si se quiere destruir también la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000, // fecha en el pasado
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Finalmente, destruir la sesión
session_destroy();

header('Location: ' . BASE_URL . 'index');
exit;
?>