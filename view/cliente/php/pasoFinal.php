<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . 'config/autoload.php';

date_default_timezone_set('America/Bogota');
header('Content-Type: application/json; charset=utf-8');

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Documento (ID) y código de tienda desde sesión
        $documento    = $_SESSION['cliente']['documento'];
        $codigoTienda = $_SESSION['cliente']['codigo_tienda'];

        // Instancia cliente
        $objCliente = new Cliente(null, $documento);
        $objControlCustomerUpdate = new ControlCustomerUpdateUser();

        if (!empty($data['instagram_user'])) {
            // Caso 1: Instagram + Tienda
            $resultado = $objControlCustomerUpdate->updateInstagramAndTienda(
                $objCliente,
                $data['instagram_user'],
                $codigoTienda
            );
            echo json_encode([
                "success" => $resultado,
                "message" => "Registro completado usuario"
            ]);
            exit;

        } else {
            // Caso 2: solo Tienda
            $resultado = $objControlCustomerUpdate->updateTienda(
                $objCliente,
                $codigoTienda
            );
            echo json_encode([
                "success" => $resultado,
                "message" => "Registro completado tienda"
                ]);
             exit;
        }




    } else {
        echo json_encode([
            "success" => false,
            "message" => "Método no permitido."
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
