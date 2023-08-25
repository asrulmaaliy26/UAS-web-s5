<?php

class Database {
    protected $conn;

    public function __construct() {
        $dbserver = "localhost";
        $dbuser = "root";
        $dbpassword = "";
        $dbname = "klinikngobat";

        try {
            $this->conn = new PDO("mysql:host=$dbserver;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

?>
