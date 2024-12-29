<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Use the absolute path
require_once(_ROOT_ . "/app/db/dbh.php");

//require_once("../app/db/dbh.php");
abstract class Model{
    protected $db;
    protected $conn;

    public function connect(){
        if(null === $this->conn ){
            $this->db = new Dbh();
            $this->conn = $this->db->getConn();
        }
        return $this->db;
    }
}
?>