<?php

class QueryDescribe {
    private $table;

    // Método para especificar la tabla a describir
    public function table($tableName) {
        $this->table = $tableName;
        return $this;
    }

    // Método para obtener la consulta DESCRIBE
    public function getQuery() {
        if (empty($this->table)) {
            throw new Exception("Table name must be specified.");
        }
        return "DESCRIBE " . $this->table;
    }
}

// Ejemplo de uso
// try {
//     $query = (new QueryDescribe())
//         ->table("usuarios");

//     echo $query->getQuery();  // Output: DESCRIBE usuarios
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage();
// }
