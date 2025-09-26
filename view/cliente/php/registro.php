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
        
        // Instancia Cliente
        $objCliente = new Cliente($data['tDocumento'],  $data['PassportNumber'], $data['FirstName'], $data['LastName'], $data['Sex'], $data['BirthDateDay'], $data['BirthDateMonth'], $data['BirthDateYear'], $data['Email'], $data['CellularPhoneNumber'], $data['CityText'], $data['RegionId'], $data['City'], $data['TermsAccepted']);
        $objControlCliente = new ControlCliente($objCliente, $objOtp);

        if ($objControlCliente->guardarDatos()) {

            $envio = $objControlOtp->enviarOtp();
            // Guardar datos del cliente
            if ($envio['resultado']['success'] && $objControlCliente->registraOtp()) {
                
                echo json_encode([
                    "success" => true,
                    "message" => "Tú código ha sido enviado.",
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