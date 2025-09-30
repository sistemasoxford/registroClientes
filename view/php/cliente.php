<?php

require_once 'config/config.php'; 
require_once BASE_PATH . 'config/autoload.php';
require_once BASE_PATH . 'config/env.php';

loadEnv();

// $login = $_ENV['SOAP_USER'] ?? '';
// $pass = $_ENV['SOAP_PASS'] ?? '';

$documento = $_POST['PassportNumber'] ?? null;

if (!$documento) {
    echo json_encode([
        "success" => 1,
        "message" => "No se recibió documento."
    ]);
    exit;
}

// Instancia Cliente
$objCliente = new Cliente(null, $documento);

// Instancia ControlSesion
$controlSesion = new ControlSesion($objCliente);

$objControlCustomerUpdate = new ControlCustomerUpdateUser();

$resultado = $objControlCustomerUpdate->selectUserCegid(
                $_SESSION['cliente']['codigo_tienda']
            );

$_SESSION['loginSoap'] = "Y2_C4_PROD\\" . $resultado;

// Config SOAP
$wsdl = "http://200.41.6.86/Y2_PROD/CustomerWcfService.svc?wsdl";
$options = [
    "trace" => 1,
    "exceptions" => true,
    "cache_wsdl" => WSDL_CACHE_NONE,
    // 'login' => $_ENV['SOAP_USER'] ?? '',
    'login' => $_SESSION['loginSoap'],
    'password' => $_ENV['SOAP_PASS'] ?? ''
];

try {
    $client = new SoapClient($wsdl, $options);

    $params = [
        "searchData" => [
            "PassportNumber" => $documento,
            "MaxNumberOfCustomers" => 1
        ],
        "clientContext" => [
            "DatabaseId" => "Y2_C4_PROD"
        ]
    ];

    $response = $client->__soapCall("SearchCustomerIds", [$params]);

    $result = $response->SearchCustomerIdsResult ?? null;

    if ($result && !empty($result->CustomerQueryData)) {
        // Normalizar a array
        $clientes = $result->CustomerQueryData;
        if (!is_array($clientes)) {
            $clientes = [$clientes];
        }

        // Usamos el primero (puedes manejar más si quieres)
        $c = $clientes[0];

        $datosCliente = [
            "CustomerId" => $c->CustomerId ?? null,
            "PassportNumber" => $c->CustomerId ?? null,
            "FirstName"  => $c->FirstName ?? null,
            "LastName"   => $c->LastName ?? null,
            "Sex"        => $c->Sex ?? null,  
            "BirthDateDay"   => $c->BirthDateData->BirthDateDay   ?? null,
            "BirthDateMonth" => $c->BirthDateData->BirthDateMonth ?? null,
            "BirthDateYear"  => $c->BirthDateData->BirthDateYear  ?? null,
            "Email"      => $c->EmailData->Email ?? null,
            "CellularPhoneNumber"      => $c->PhoneData->CellularPhoneNumber ?? null,
            "AddressLine1" => $c->AddressData->AddressLine1 ?? null,
            "RegionId"    => $c->AddressData->RegionId ?? null,
            "City"       => $c->AddressData->City ?? null
            
        ];

        // Guardar en sesión
        $controlSesion->iniciarSesion($datosCliente);

        echo json_encode([
            "success" => true,
            "message" => "Cliente encontrado.",
            "cliente" => $datosCliente
        ]);
    } else {
        // Guardar solo cédula
        $controlSesion->iniciarSesion();

        echo json_encode([
            "success" => false,
            "message" => "Cliente no encontrado, continuar con registro.",
            "cliente" => ["PassportNumber" => $documento]
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en SOAP: " . $e->getMessage()
    ]);
}
