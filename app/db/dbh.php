<?php

require_once("config.php"); // Ensure this file contains your database credentials

class Dbh {
    private $servername;
    private $username;
    private $password;
    private $dbname;

    private $conn;
    private $result;
    public $sql;

    // Constructor to initialize database connection
    function __construct() {
        $this->servername = DB_SERVER; // Defined in config.php
        $this->username = DB_USER;     // Defined in config.php
        $this->password = DB_PASS;     // Defined in config.php
        $this->dbname = DB_DATABASE;   // Defined in config.php
        $this->connect();
    }

    // Method to establish a database connection
    public function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn; // Return the connection object
    }

    // Method to get the connection
    public function getConn() {
        return $this->conn;
    }

    // Method to execute a query
    function query($sql) {
        if (!empty($sql)) {
            $this->sql = $sql;
            $this->result = $this->conn->query($sql);
            return $this->result;
        } else {
            return false;
        }
    }

    // Method to fetch a single row from the result
    function fetchRow($result = "") {
        if (empty($result)) { 
            $result = $this->result; 
        }
        return $result->fetch_assoc();
    }

    // Destructor to close the connection
    function __destruct() {
        $this->conn->close();
    }
}
?>