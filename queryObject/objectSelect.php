<?php

class QuerySelect {
    private $select;
    private $from;
    private $joins;
    private $where;
    private $groupBy;
    private $orderBy;
    private $limit;
    private $offset;
    private $unions = []; // Almacena las consultas para UNION

    public function select($columns) {
        $this->select = "SELECT " . $columns;
        return $this;
    }

    public function from($table) {
        $this->from = "FROM " . $table;
        return $this;
    }

    public function where($condition, $logicalOperator = 'AND') {
        if (empty($this->where)) {
            $this->where = "WHERE " . $condition;
        } else {
            $this->where .= " $logicalOperator " . $condition;
        }
        return $this;
    }

    public function innerJoin($table, $onCondition) {
        $this->joins[] = "INNER JOIN $table ON $onCondition";
        return $this;
    }

    public function leftJoin($table, $onCondition) {
        $this->joins[] = "LEFT JOIN $table ON $onCondition";
        return $this;
    }

    public function groupBy($columns) {
        $this->groupBy = "GROUP BY " . $columns;
        return $this;
    }

    public function orderBy($column, $order = 'ASC') {
        if (empty($this->orderBy)) {
            $this->orderBy = "ORDER BY $column $order";
        } else {
            $this->orderBy .= ", $column $order";
        }
        return $this;
    }

    public function limit($limit) {
        $this->limit = "LIMIT " . $limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = "OFFSET " . $offset;
        return $this;
    }

    public function whereIn($column, $values, $logicalOperator = 'AND') {
        $inValues = implode(', ', array_map(function($value) {
            return "'" . $value . "'";
        }, $values));

        $condition = "$column IN ($inValues)";

        if (empty($this->where)) {
            $this->where = "WHERE " . $condition;
        } else {
            $this->where .= " $logicalOperator " . $condition;
        }

        return $this;
    }

    // Agregar una nueva consulta al conjunto de UNION
    public function union(QuerySelect $query, $unionType = 'UNION') {
        $this->unions[] = "$unionType " . $query->getQuery();
        return $this;
    }

    public function getQuery() {
        $query = "{$this->select} {$this->from}";

        if (!empty($this->joins)) {
            $query .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $query .= " $this->where";
        }

        if (!empty($this->groupBy)) {
            $query .= " $this->groupBy";
        }

        if (!empty($this->orderBy)) {
            $query .= " $this->orderBy";
        }

        if (!empty($this->limit)) {
            $query .= " $this->limit";
        }

        if (!empty($this->offset)) {
            $query .= " $this->offset";
        }

        // Agregar consultas UNION si existen
        if (!empty($this->unions)) {
            $query = '(' . $query . ') ' . implode(' ', $this->unions);
        }

        return $query;
    }
}


// Ejemplo de uso de la función groupBy
// $query = (new QuerySelect())
//     ->select("usuarios.nombre, COUNT(pedidos.id) as total_pedidos")
//     ->from("usuarios")
//     ->innerJoin("pedidos", "usuarios.id = pedidos.usuario_id")
//     ->where("usuarios.tipo = 'premium'")
//     ->whereIn("usuarios.tipo", $usuariosPremium)
//     ->groupBy("usuarios.nombre")
//     ->orderBy("total_pedidos", "DESC");

//echo $query->getQuery();
