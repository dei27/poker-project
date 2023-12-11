<?php
require_once('db.php');

class BaseModel {
    protected $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function __destruct() {
        $this->conn = null; // Cerrar la conexión cuando el objeto se destruye
    }
}

