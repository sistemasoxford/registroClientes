<?php

// Asegúrate de que se carga el archivo .env
require_once BASE_PATH . '/config/autoload.php';
require_once BASE_PATH . '/config/env.php';
loadEnv();

class ControlConexion {
    private static $instancia;
    public $conn;

    private function __construct() {
        $this->conn = null;
    }

    public static function getInstance() {
        if (self::$instancia === null) {
            self::$instancia = new ControlConexion();
        }
        return self::$instancia;
    }

    // Abrir conexión
    public function abrirBd() {
        try {
            $host = $_ENV['DB_HOST_PROD'] ?? $_ENV['DB_HOST_DEV'];
            $usuario = $_ENV['DB_USER_PROD'] ?? $_ENV['DB_USER_DEV'];
            $clave = $_ENV['DB_PASS_PROD'] ?? $_ENV['DB_PASS_DEV'];
            $baseDeDatos = $_ENV['DB_NAME_PROD'] ?? $_ENV['DB_NAME_DEV'];

            $this->conn = new mysqli($host, $usuario, $clave, $baseDeDatos);

            if ($this->conn->connect_errno) {
                printf("Connect failed: %s\n", $this->conn->connect_error);
                exit();
            }

            $this->conn->set_charset("utf8mb4");

        } catch (Exception $e) {
            echo "ERROR AL CONECTARSE AL SERVIDOR: " . $e->getMessage() . "\n";
        }
    }

    // Cerrar conexión
    public function cerrarBd() {
        try {
            if ($this->conn !== null) {
                $this->conn->close();
            }
        } catch (Exception $e) {
            echo "ERROR AL CERRAR LA CONEXIÓN: " . $e->getMessage();
        }
    }

    // Ejecutar INSERT, UPDATE, DELETE con objetos QueryUpdate/QueryInsert
    public function ejecutarComandoSql($sql) {
        $registros_afectados = 0;
        try {
            if ($this->conn === null) {
                throw new Exception("No hay una conexión a la base de datos");
            }

            $resultado = $this->conn->query($sql->getQuery());

            if ($resultado !== false) {
                $registros_afectados = $this->conn->affected_rows;
            } else {
                throw new Exception("Error al ejecutar el comando SQL: " . $this->conn->error);
            }

        } catch (Exception $e) {
            throw $e;
        }

        return $registros_afectados;
    }

    // Ejecutar SELECT con objetos QuerySelect
    public function ejecutarSelect($sql) {
        try {
            if ($this->conn === null) {
                throw new Exception("No hay una conexión a la base de datos");
            }

            $recordSet = $this->conn->query($sql->getQuery());

            if ($recordSet === false) {
                throw new Exception("Error al ejecutar la consulta SELECT: " . $this->conn->error);
            }

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }

        return $recordSet;
    }

    // Nuevo: ejecutar SELECT directo desde string
    public function ejecutarSelectRaw(string $sql) {
        try {
            if ($this->conn === null) {
                throw new Exception("No hay una conexión a la base de datos");
            }

            $recordSet = $this->conn->query($sql);

            if ($recordSet === false) {
                throw new Exception("Error al ejecutar la consulta SELECT: " . $this->conn->error);
            }

            return $recordSet;

        } catch (Exception $e) {
            echo "Error en ejecutarSelectRaw: " . $e->getMessage();
            return false;
        }
    }

    // Nuevo: ejecutar UPDATE/INSERT/DELETE desde string
    public function ejecutarComandoSqlRaw(string $sql) {
        $registros_afectados = 0;
        try {
            if ($this->conn === null) {
                throw new Exception("No hay una conexión a la base de datos");
            }

            $resultado = $this->conn->query($sql);

            if ($resultado !== false) {
                $registros_afectados = $this->conn->affected_rows;
            } else {
                throw new Exception("Error al ejecutar el comando SQL: " . $this->conn->error);
            }

        } catch (Exception $e) {
            echo "Error en ejecutarComandoSqlRaw: " . $e->getMessage();
            return false;
        }

        return $registros_afectados;
    }

    // Obtener último ID insertado
    public function getLastInsertId() {
        try {
            if ($this->conn === null) {
                throw new Exception("No hay una conexión a la base de datos");
            }
            return $this->conn->insert_id;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Manejo de transacciones
    public function iniciarTransaccion() {
        if ($this->conn === null) {
            throw new Exception("No hay una conexión a la base de datos");
        }
        $this->conn->begin_transaction();
    }

    public function confirmarTransaccion() {
        if ($this->conn === null) {
            throw new Exception("No hay una conexión a la base de datos");
        }
        $this->conn->commit();
    }

    public function revertirTransaccion() {
        if ($this->conn === null) {
            throw new Exception("No hay una conexión a la base de datos");
        }
        $this->conn->rollback();
    }
}
