<?php

require_once BASE_PATH . '/config/config.php';
require_once(QUERY_OBJECT_PATH . 'objectInsert.php');
require_once(QUERY_OBJECT_PATH . 'objectUpdate.php');

class ControlCliente {
    var $objCliente;
    var $objOtp;
    private $objControlConexion;

    function __construct($objCliente = null, $objOtp = null) {
        $this->objCliente = $objCliente;
        $this->objOtp = $objOtp;
        $this->objControlConexion = ControlConexion::getInstance();
    }

    function registrarCliente(){
        $tDocumento = $_SESSION['cliente']['tDocumento'];
        $documentoOriginal = $this->objCliente->getDocumento();
        $documento = $_SESSION['cliente']['documento'];
        $nombres = $_SESSION['cliente']['nombres'];
        $apellidos = $_SESSION['cliente']['apellidos'];
        $fechaNacimiento = (new DateTime($_SESSION['cliente']['anioNacimiento'] . '-' . $_SESSION['cliente']['mesNacimiento'] . '-' . $_SESSION['cliente']['diaNacimiento']))->format('Y-m-d');
        $email = $_SESSION['cliente']['email'];
        $sexo = $_SESSION['cliente']['sexo'];
        $telefono = $_SESSION['cliente']['telefono'];
        $direccion = $_SESSION['cliente']['direccion'];
        $ciudad = $_SESSION['cliente']['ciudad'];
        $region = $_SESSION['cliente']['region'];
        $terminos = $_SESSION['cliente']['terminos'];
        $otp = $this->objOtp->getOtp();

        // Conexión a la base de datos
        $queryUpdate = new QueryUpdate();
        $comandoSql = $queryUpdate->table("habeas_data")
                                  ->set(["tDocumento" => $tDocumento, "id" => $documento, "nombre" => $nombres, "apellido" => $apellidos, "sexo" => $sexo, "fecha_nacimiento" => $fechaNacimiento, "email" => $email, "telefono" => $telefono, "direccion" => $direccion, "ciudad" => $ciudad, "region" => $region, "terminos" => $terminos])
                                  ->where("id = '$documentoOriginal' AND otp = '$otp'");

        try{
            $this->objControlConexion->abrirBd();
            $recordSet = $this->objControlConexion->ejecutarComandoSql($comandoSql);

            if($recordSet > 0){
                return ["success" => true, "message" => "Usuario creado con exito."];
            }else{
                return ["success" => false, "message" => "Error al crear el usuario. Por favor, intente de nuevo."];
            }

        }catch(Exception $e){
            throw new Exception("Error durante la conexión a la base de datos: " . $e->getMessage());
        }finally{
            $this->objControlConexion->cerrarBd();
        }
    }

    function registraOtp(){
        $documento = $this->objCliente->getDocumento();
        $otp = $this->objOtp->getOtp();

        // Conexión a la base de datos
        $queryInsert = new QueryInsert();
        $comandoSql = $queryInsert->into("habeas_data")
                                  ->columns(["id", "otp"])
                                  ->values([$documento, $otp]);

        try{
            $this->objControlConexion->abrirBd();
            $recordSet = $this->objControlConexion->ejecutarComandoSql($comandoSql);

            if($recordSet > 0){
                return ["success" => true, "message" => "Usuario creado con exito."];
            }else{
                return ["success" => false, "message" => "Error al subir el archivo. Por favor, intente de nuevo con otro tipo de archivo."];
            }

        }catch(Exception $e){
            throw new Exception("Error durante la conexión a la base de datos: " . $e->getMessage());
        }finally{
            $this->objControlConexion->cerrarBd();
        }
    }

    function guardarDatos() {
        // Asegurar que la sesión esté activa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Inicializar array si no existe
        if (!isset($_SESSION['cliente'])) {
            $_SESSION['cliente'] = [];
        }

        // Datos a guardar
        $datos = [
            'tDocumento'    =>  $this->objCliente->getTDocumento(),
            'documento'       => $this->objCliente->getDocumento(),
            'nombres'         => $this->objCliente->getNombre(),
            'apellidos'       => $this->objCliente->getApellido(),
            'sexo'            => $this->objCliente->getSexo(),
            'diaNacimiento'   => $this->objCliente->getDia(),
            'mesNacimiento'   => $this->objCliente->getMes(),
            'anioNacimiento'  => $this->objCliente->getAnio(),
            'email'           => $this->objCliente->getCorreo(),
            'telefono'        => $this->objCliente->getCelular(),
            'direccion'       => $this->objCliente->getDireccion(),
            'ciudad'          => $this->objCliente->getCiudad(),
            'region'          => $this->objCliente->getDepartamento(),
            'terminos'        => $this->objCliente->getTerminos()
        ];

        $guardado = false;

        // Solo actualizar campos con valores válidos
        foreach ($datos as $campo => $valor) {
            if (!empty($valor)) {
                $_SESSION['cliente'][$campo] = $valor;
                $guardado = true;
            }
        }

        return $guardado;
    }

}