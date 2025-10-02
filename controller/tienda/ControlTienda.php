<?php

require_once BASE_PATH . '/config/config.php';
require_once(QUERY_OBJECT_PATH . 'objectInsert.php');
require_once(QUERY_OBJECT_PATH . 'objectUpdate.php');
require_once(QUERY_OBJECT_PATH . 'objectSelect.php');

class ControlTienda {
    var $objTienda;
    private $objControlConexion;

    function __construct($objTienda = null) {
        $this->objTienda = $objTienda;
        $this->objControlConexion = ControlConexion::getInstance();
    }

    function buscarTienda(){
        $tienda = $this->objTienda->getCodigo();

        $querySelect = new QuerySelect();
        $comandoSql = $querySelect->select("t.codigo AS codigo_tienda, t.nombre AS nombre_tienda, c.postal AS codigo_postal, d.codigo AS codigo_departamento, c.nombre AS nombre_ciudad")
                                  ->from("tiendas t")->leftJoin("ciudadxtiendas ct", "t.id = ct.idtienda")
                                  ->leftJoin("ciudades c", "ct.idciudad = c.id")
                                  ->leftJoin("departamentosxciudades dc", "c.id = dc.idciudad")
                                  ->leftJoin("departamentos d", "dc.iddepartamento = d.id")
                                  ->where("t.codigo = '$tienda'");

        try{
            $this->objControlConexion->abrirBd();
            $recordSet = $this->objControlConexion->ejecutarSelect($comandoSql);

            if ($recordSet->num_rows > 0) {
                $resultado = $recordSet->fetch_assoc();

                // Validar si los campos vienen vacíos o NULL
                if (empty($resultado['codigo_tienda']) || empty($resultado['nombre_tienda'])) {
                    return ["success" => false, "message" => "La tienda existe pero los datos están vacíos."];
                }

                return ["success" => true, "data" => $resultado];
            } else {
                return ["success" => false, "message" => "No se encontró la tienda."];
            }
        }catch(Exception $e){
            throw new Exception("Error durante la conexión a la base de datos: " . $e->getMessage());
        }finally{
            $this->objControlConexion->cerrarBd();
        }
    }
}