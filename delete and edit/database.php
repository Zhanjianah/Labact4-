<?php

class Library {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "library";
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException("Connection failed: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>