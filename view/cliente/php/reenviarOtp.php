<?php

session_start();
require_once 'config/config.php';
require_once BASE_PATH . 'config/autoload.php';

date_default_timezone_set('America/Bogota');

header('Content-Type: application/json; charset=utf-8');

try {
    $data = json_decode(file_get_contents("php://input"), true);
    // Verificar si se recibieron los datos del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && (!empty($data['enviar']))) {
        $objOtp = new Otp("+57" . $_SESSION['cliente']['telefono']);
        $objControlOtp = new ControlOtp($objOtp);
        $objCliente = new Cliente(null,$_SESSION['cliente']['documento']);
        $objControlCliente = new ControlCliente($objCliente, $objOtp);
        $envio = $objControlOtp->enviarOtp();
        if ($envio['resultado']['success']  && $objControlCliente->registraOtp()) {
            echo json_encode([
                "success" => true,
                "message" => "Tú código ha sido reenviado.",
                "otp_enviado" => true
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al reenviar OTP.",
                "error" => $envio['resultado']
            ]);
        }
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
    exit;
}
        