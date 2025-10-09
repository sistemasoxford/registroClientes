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
        $objCliente = new Cliente(tDocumento: null, documento: $_SESSION['cliente']['documento'], medioEnvio: $_SESSION['cliente']['medioEnvio']);
        $objOtp = new Otp(null, null, $data['otp']);
        $objControlOtp = new ControlOtp($objOtp, $objCliente);

        $verificacion = $objControlOtp->validarOtp();

        if($verificacion['success']) {
            $objControlCliente = new ControlCliente($objCliente, $objOtp);
            $registrar = $objControlCliente->registrarCliente();

            if($_SESSION['urlOtp'] != 1){
                $objControlCustomerUpdate = new ControlCustomerUpdate();
                $registrarCegid = $objControlCustomerUpdate->updateCustomer();
            }else{
                $objControlCustomer = new ControlCustomer();
                $registrarCegid = $objControlCustomer->addNewCustomer();
            }

            if($registrar['success'] && $registrarCegid['success']) {
                // Eliminar la variable de sesión del cliente
                // unset($_SESSION['cliente']);
                // unset($_SESSION['usuario']);
                // unset($_SESSION['urlOtp']);
                // session_destroy(); // Destruir toda la sesión si no se necesita para otros propósitos
                echo json_encode([
                    "success" => $verificacion['success'],
                    "message" => $verificacion['message']
                ]);
            }else{
                echo json_encode([
                    "success" => false,
                    "message" => $registrarCegid['response']
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
            "message" => "Por favor digite el código de verificación"
        ]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}

