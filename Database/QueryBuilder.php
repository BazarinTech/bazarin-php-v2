<?php 
class QueryBuilder {
    public function __construct($db) {
        $this->conn = $db;
    }

    public function select($table, $columns = '*', $conditions = [], $orderBy = null) {
        $sql = "SELECT $columns FROM $table";

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', array_map(fn($col) => "$col = ?", array_keys($conditions)));
        }

        if ($orderBy) {
            $sql .= " ORDER BY " . $orderBy['column'] . " " . strtoupper($orderBy['direction']);
        }

        $stmt = $this->conn->prepare($sql);
        if (!empty($conditions)) {
            $stmt->bind_param(str_repeat('s', count($conditions)), ...array_values($conditions));
        }

        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insert($table, $data) {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($data)), ...array_values($data));
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function update($table, $data, $conditions) {
        $setClause = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));
        $whereClause = implode(' AND ', array_map(fn($col) => "$col = ?", array_keys($conditions)));

        $sql = "UPDATE $table SET $setClause WHERE $whereClause";
        $stmt = $this->conn->prepare($sql);

        $params = array_merge(array_values($data), array_values($conditions));
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);

        $stmt->execute();

        return $stmt->affected_rows; // Returns the number of affected rows
    }

    public function delete($table, $conditions) {
        $whereClause = implode(' AND ', array_map(fn($col) => "$col = ?", array_keys($conditions)));

        $sql = "DELETE FROM $table WHERE $whereClause";
        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(str_repeat('s', count($conditions)), ...array_values($conditions));

        $stmt->execute();

        return $stmt->affected_rows; // Returns the number of affected rows
    }
}

