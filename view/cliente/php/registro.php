<?php

session_start();
require_once 'config/config.php';
require_once BASE_PATH . 'config/autoload.php';

date_default_timezone_set('America/Bogota');

header('Content-Type: application/json; charset=utf-8');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    // Verificar si se recibieron los datos del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && (!empty($data['tDocumento']) && !empty($data['PassportNumber']) && !empty($data['FirstName']) && !empty($data['LastName']) && !empty($data['BirthDateDay']) && !empty($data['BirthDateMonth']) && !empty($data['BirthDateYear']) && !empty($data['Email']) && !empty($data['CellularPhoneNumber']) && !empty($data['CityText']) && !empty($data['City']) && !empty($data['RegionId']) && !empty($data['TermsAccepted']))) {
        
        $objOtp = new Otp("+57" . $data['CellularPhoneNumber']);
        $objControlOtp = new ControlOtp($objOtp);
        $_SESSION['cliente']['email'] = $data['Email'];
        $_SESSION['cliente']['medioEnvio'] = $data['canal'];
        // Instancia Cliente
        $objCliente = new Cliente($data['tDocumento'],  $data['PassportNumber'], $data['FirstName'], $data['LastName'], $data['Sex'], $data['BirthDateDay'], $data['BirthDateMonth'], $data['BirthDateYear'], $data['Email'], $data['CellularPhoneNumber'], $data['CityText'], $data['RegionId'], $data['City'], $data['TermsAccepted'], $data['canal'] ?? 'sms');
        $objControlCliente = new ControlCliente($objCliente, $objOtp);

        if ($objControlCliente->guardarDatos()) {

            $envio = $objControlOtp->enviarOtp();
            // Guardar datos del cliente
            if ($envio['resultadoSMS']['success'] && $objControlCliente->registraOtp()) {
                
                echo json_encode([
                    "success" => true,
                    "message" => $envio['resultadoCorreo']['message'],
                    "otp_enviado" => true
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Error al guardar los datos.",
                    "otp_enviado" => true
                ]);
            }

        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al enviar OTP.",
                "error" => $envio['resultado']
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