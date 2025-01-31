<?php 
class QueryBuilder {
    public function __construct($db) {
        $this->conn = $db;
    }

public function select($table, $columns = '*', $conditions = [], $orderBy = null, $limit = null) {
    $sql = "SELECT $columns FROM $table";

    if (!empty($conditions)) {
        $conditionStrings = [];
        foreach ($conditions as $col => $val) {
            if (preg_match('/[><=]+/', $col, $matches)) {
                // Extract column name and operator (e.g., 'lastActive >' => column: lastActive, operator: >)
                $operator = $matches[0];
                $col = explode(' ', $col)[0];
                $conditionStrings[] = "$col $operator ?";
            } else {
                $conditionStrings[] = "$col = ?";
            }
        }
        $sql .= " WHERE " . implode(' AND ', $conditionStrings);
    }

    if ($orderBy) {
        $sql .= " ORDER BY " . $orderBy['column'] . " " . strtoupper($orderBy['direction']);

        if ($limit) {
            $sql .= " LIMIT " . $limit['value'];
        }
    }

    $stmt = $this->conn->prepare($sql);
    if (!empty($conditions)) {
        $stmt->bind_param(str_repeat('s', count($conditions)), ...array_values($conditions));
    }

    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

    public function selectOR($table, $columns = '*', $conditions = [], $orderBy = null) {
        $sql = "SELECT $columns FROM $table";

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' OR ', array_map(fn($col) => "$col = ?", array_keys($conditions)));
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

    public function update($table, $data, $conditions = []) {
    // Generate the SET clause
    $setClause = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));

    // Check if conditions are provided
    $whereClause = '';
    if (!empty($conditions)) {
        $whereClause = " WHERE " . implode(' AND ', array_map(fn($col) => "$col = ?", array_keys($conditions)));
    }

    // Construct the SQL query
    $sql = "UPDATE $table SET $setClause" . $whereClause;
    $stmt = $this->conn->prepare($sql);

    // Bind parameters
    $params = array_merge(array_values($data), array_values($conditions));
    if (!empty($params)) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }

    // Execute the query
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
    public function auth($table, $uname, $password){
        $select = $this->select($table, '*', ['username' => $uname, 'passwrd' => $password]);
        $num = count($select);
        if ($num > 0) {
            return ['Status' => 'Success', 'Data' => $select, 'Message' => 'Authentication Successfull'];
        }else{
            return ['Status' => 'Failed', 'Data' => '', 'Message' => 'Invalid Credintials'];
        }
    }
    public function randomly($table, $columns = '*', $conditions = [], $limit = null) {
        // Start with the basic SQL query
        $sql = "SELECT $columns FROM $table";
    
        // Add conditions to the query if they are provided
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', array_map(fn($col) => "$col = ?", array_keys($conditions)));
        }
    
        // Add random order to the query
        $sql .= " ORDER BY RAND()";
    
        // Apply limit if provided
        if ($limit) {
            $sql .= " LIMIT " . $limit['value'];
        }
    
        // Prepare the SQL statement
        $stmt = $this->conn->prepare($sql);
    
        // Bind parameters if there are conditions
        if (!empty($conditions)) {
            $stmt->bind_param(str_repeat('s', count($conditions)), ...array_values($conditions));
        }
    
        // Execute the statement
        $stmt->execute();
    
        // Get the result
        $result = $stmt->get_result();
        
        // Return the fetched data as an associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

