<?php

require_once BASE_PATH . '/config/config.php';

class ControlCustomerUpdateUser {
    private $db;

    public function __construct() {
        $this->db = ControlConexion::getInstance();
    }

    // Actualizar Instagram y Tienda en el último registro de un cliente
    public function updateInstagramAndTienda($cliente, $instagram, $codigoTienda) {
        try {
            $documentoCliente = $cliente->getDocumento(); // corresponde a la columna "id" o "documento"

            $this->db->abrirBd();

            // Obtener el último registro según created_at
            $result = $this->db->ejecutarSelectRaw(
                "SELECT id, created_at 
                 FROM habeas_data 
                 WHERE id = '$documentoCliente' 
                 ORDER BY created_at DESC 
                 LIMIT 1"
            );

            if ($result === false || $result->num_rows === 0) {
                $this->db->cerrarBd();
                return false;
            }

            $row = $result->fetch_assoc();
            $ultimoRegistroId = $row['id'];
            $createdAt = $row['created_at'];

            // Escapar valores
            $instagram = $this->db->conn->real_escape_string($instagram);
            $codigoTienda = $this->db->conn->real_escape_string($codigoTienda);

            // Actualizar solo ese registro usando (id + created_at)
            $sqlUpdate = "UPDATE habeas_data 
                          SET usuario_instagram = '$instagram', codigo_tienda = '$codigoTienda'
                          WHERE id = '$ultimoRegistroId' AND created_at = '$createdAt'";

            $resultUpdate = $this->db->ejecutarComandoSqlRaw($sqlUpdate);
            $this->db->cerrarBd();

            return $resultUpdate > 0;

        } catch (Exception $e) {
            return false;
        }
    }

    // Actualizar solo Tienda en el último registro de un cliente
    public function updateTienda($cliente, $codigoTienda) {
        try {
            $documentoCliente = $cliente->getDocumento();

            $this->db->abrirBd();

            // Obtener el último registro según created_at
            $result = $this->db->ejecutarSelectRaw(
                "SELECT id, created_at 
                 FROM habeas_data 
                 WHERE id = '$documentoCliente' 
                 ORDER BY created_at DESC 
                 LIMIT 1"
            );

            if ($result === false || $result->num_rows === 0) {
                $this->db->cerrarBd();
                return false;
            }

            $row = $result->fetch_assoc();
            $ultimoRegistroId = $row['id'];
            $createdAt = $row['created_at'];

            // Escapar valor
            $codigoTienda = $this->db->conn->real_escape_string($codigoTienda);

            // Actualizar solo ese registro
            $sqlUpdate = "UPDATE habeas_data 
                          SET codigo_tienda = '$codigoTienda'
                          WHERE id = '$ultimoRegistroId' AND created_at = '$createdAt'";

            $resultUpdate = $this->db->ejecutarComandoSqlRaw($sqlUpdate);
            $this->db->cerrarBd();

            return $resultUpdate > 0;

        } catch (Exception $e) {
            return false;
        }
    }


public function selectUserCegid($codigoTienda) {
    try {
        $this->db->abrirBd();
        

        // Obtener el último registro según created_at
        $result = $this->db->ejecutarSelectRaw(
            "SELECT usuario_cegid
             FROM usuarios_cegid
             WHERE codigo_tienda = '$codigoTienda'"
        );

        if ($result === false || $result->num_rows === 0) {
            $this->db->cerrarBd();
            return false;
        }

        // Extraer el valor de usuario_cegid
        $row = $result->fetch_assoc();
        
        $this->db->cerrarBd();

        return $row['usuario_cegid'];

    } catch (Exception $e) {
        return false;
    }
}
}
