<?php

class QueryBuilder {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // SELECT Method
    public function select($table, $columns = '*', $conditions = [], $orderBy = null, $limit = null) {
        $sql = "SELECT " . (is_array($columns) ? implode(", ", $columns) : $columns) . " FROM $table";
        $params = [];
        
        if (!empty($conditions)) {
            $conditionStrings = [];
            foreach ($conditions as $col => $val) {
                if (preg_match('/\s*(<|>|<=|>=|!=|=)\s*$/', $col, $matches)) {
                    $operator = $matches[1];
                    $col = preg_replace('/\s*(<|>|<=|>=|!=|=)\s*$/', '', $col);
                } else {
                    $operator = '=';
                }
                $conditionStrings[] = "$col $operator ?";
                $params[] = $val;
            }
            $sql .= " WHERE " . implode(" AND ", $conditionStrings);
        }

        if ($orderBy) {
            $sql .= " ORDER BY " . $orderBy['column'] . " " . strtoupper($orderBy['direction']);
        }

        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $types = '';
            foreach ($params as $param) {
                $types .= is_int($param) ? 'i' : (is_float($param) ? 'd' : 's');
            }
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // SELECT using OR conditions
    public function selectOR($table, $columns = '*', $conditions = [], $orderBy = null, $limit = null) {
        $sql = "SELECT " . (is_array($columns) ? implode(", ", $columns) : $columns) . " FROM $table";
        $params = [];
        
        if (!empty($conditions)) {
            $conditionStrings = [];
            foreach ($conditions as $col => $val) {
                $conditionStrings[] = "$col = ?";
                $params[] = $val;
            }
            $sql .= " WHERE " . implode(" OR ", $conditionStrings);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY " . $orderBy['column'] . " " . strtoupper($orderBy['direction']);
        }
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // AUTHENTICATION
    public function auth($table, $uname, $password) {
        $user = $this->select($table, '*', ['username' => $uname]);
        
        if (!empty($user) && password_verify($password, $user[0]['password'])) {
            return ['Status' => 'Success', 'Data' => $user, 'Message' => 'Authentication Successful'];
        }
        return ['Status' => 'Failed', 'Data' => '', 'Message' => 'Invalid Credentials'];
    }

    // DELETE Method
    public function delete($table, $conditions) {
        $sql = "DELETE FROM $table";
        $params = [];
        
        if (!empty($conditions)) {
            $conditionStrings = [];
            foreach ($conditions as $col => $val) {
                $conditionStrings[] = "$col = ?";
                $params[] = $val;
            }
            $sql .= " WHERE " . implode(" AND ", $conditionStrings);
        }
        
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        return $stmt->execute();
    }
}

