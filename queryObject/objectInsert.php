<?php

class QueryInsert {
    private $table;
    private $columns;
    private $values;
    private $selectQuery; // Nuevo atributo para almacenar la consulta SELECT

    public function into($table) {
        $this->table = "INTO " . $table;
        return $this;
    }

    public function columns($columns) {
        $this->columns = "(" . implode(', ', $columns) . ")";
        return $this;
    }

    public function values($values) {
        $this->values = "VALUES (" . implode(', ', array_map(function($value) {
            return "'" . $value . "'";
        }, $values)) . ")";
        return $this;
    }

    // Nuevo método para aceptar una consulta SELECT
    public function select(QuerySelect $querySelect) {
        $this->selectQuery = $querySelect->getQuery();
        return $this;
    }

    public function getQuery() {
        if ($this->selectQuery) {
            return "INSERT {$this->table} {$this->columns} {$this->selectQuery}";
        } else {
            return "INSERT {$this->table} {$this->columns} {$this->values}";
        }
    }
}

