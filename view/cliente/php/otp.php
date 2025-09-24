<?php

session_start();
require_once 'config/config.php';
require_once BASE_PATH . 'config/autoload.php';

date_default_timezone_set('America/Bogota');

header('Content-Type: application/json; charset=utf-8');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    // Verificar si se recibieron los datos del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && (!empty($data['otp']))) {
        
        // Instancia Cliente
        $objCliente = new Cliente(null, $_SESSION['cliente']['documento']);
        $objOtp = new Otp(null, null, $data['otp']);
        $objControlOtp = new ControlOtp($objOtp, $objCliente);

        $verificacion = $objControlOtp->validarOtp();

        if($verificacion['success']) {
            $objControlCliente = new ControlCliente($objCliente, $objOtp);
            $registrar = $objControlCliente->registrarCliente();
            if($registrar['success']) {
                // Eliminar la variable de sesión del cliente
                unset($_SESSION['cliente']);
                unset($_SESSION['usuario']);
                // session_destroy(); // Destruir toda la sesión si no se necesita para otros propósitos
                echo json_encode([
                    "success" => $verificacion['success'],
                    "message" => $verificacion['message']
                ]);
            }
            
        } else {
            echo json_encode([
                "success" => $verificacion['success'],
                "message" => $verificacion['message']
            ]);
        }

    } else {
        echo json_encode([
            "success" => false,
            "message" => "No se recibieron datos del formulario."
        ]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}

