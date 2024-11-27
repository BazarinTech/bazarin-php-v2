<?php

class Connection {
    private $host;
    private $uname;
    private $pwd;
    private $db;
    private $conn;

    public function __construct($host, $uname, $pwd, $db) {
        $this->host = $host;
        $this->uname = $uname;
        $this->pwd = $pwd;
        $this->db = $db;
    }

    public function connect() {
        try {
            // Enable exceptions for mysqli
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            // Establish connection
            $this->conn = new mysqli($this->host, $this->uname, $this->pwd, $this->db);

            // Set charset (optional, but recommended)
            $this->conn->set_charset("utf8mb4");

            return $this->conn; // Return the connection object
        } catch (mysqli_sql_exception $e) {
            // Handle connection errors
            throw new RuntimeException("Error connecting to database: " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
