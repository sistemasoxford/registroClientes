<?php

class QueryUpdate {
    private $table;
    private $setValues;
    private $whereClause;

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function set($values) {
        $this->setValues = "SET " . implode(', ', array_map(function ($column, $value) {
            // Assuming values are strings, you may need to adjust this based on your needs
            return "{$column} = '{$value}'";
        }, array_keys($values), $values));
        return $this;
    }

    public function setRaw($values) {
        $this->setValues = "SET " . implode(', ', array_map(function ($column, $value) {
            return "{$column} = {$value}";
        }, array_keys($values), $values));
        return $this;
    }

    public function where($condition) {
        // Allow complex conditions or subqueries
        $this->whereClause = "WHERE " . $condition;
        return $this;
    }

    public function and($condition) {
        $this->whereClause .= " AND " . $condition;
        return $this;
    }

    public function or($condition) {
        $this->whereClause .= " OR " . $condition;
        return $this;
    }

    public function getQuery() {
        return "UPDATE {$this->table} {$this->setValues} {$this->whereClause}";
    }
}

// Uso de ejemplo
// $queryUpdate = new QueryUpdate();
// $queryUpdate->table("tabla_usuarios")
//             ->set(["nombre" => "Juan", "edad" => 26])
//             ->where("id = 1")
//             ->andWhere("idEstado = 1");

?>
