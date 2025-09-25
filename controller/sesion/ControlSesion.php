<?php
session_start();

require_once BASE_PATH . '/config/config.php';

class ControlSesion {
    var $objCliente;

    function __construct($objCliente = null) {
        $this->objCliente = $objCliente;
    }

    function iniciarSesion($datosCliente = null) {
        $documento = $this->objCliente->getDocumento();

        if ($datosCliente && is_array($datosCliente)) {
            // Guardar todos los datos si existe en SOAP
            $_SESSION['usuario'] = [
                "CustomerId" => $datosCliente['CustomerId'] ?? null,
                "PassportNumber" => $documento,
                "FirstName"    => $datosCliente['FirstName'] ?? null,
                "LastName"  => $datosCliente['LastName'] ?? null,
                "Sex"      => $datosCliente['Sex'] ?? null,
                "BirthDateDay"   => $datosCliente['BirthDateDay'] ?? null,
                "BirthDateMonth" => $datosCliente['BirthDateMonth'] ?? null,
                "BirthDateYear"  => $datosCliente['BirthDateYear'] ?? null,
                "Email"     => $datosCliente['Email'] ?? null,
                "CellularPhoneNumber"  => $datosCliente['CellularPhoneNumber'] ?? null,
                "AddressLine1" => $datosCliente['AddressLine1'] ?? null,
                "City"    => $datosCliente['City'] ?? null,
                "RegionId"      => $datosCliente['RegionId'] ?? null,
            ];
        } else {
            // Guardar solo la cédula si no existe
            $_SESSION['usuario'] = [
                "PassportNumber" => $documento
            ];
        }
    }
}
