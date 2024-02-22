<?php
require_once('dbModel.php');

class MetodoPago extends BaseModel {

    private $id;
    private $nombre;

    public function setIdPago($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }


    public function __construct($id = null, $nombre = null) {
        parent::__construct();
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function getAllMetodosPagos() {
        try {
            $query = "SELECT * FROM metodos_pagos";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function nuevoMetodoPago() {
        try {

            $query = "INSERT INTO metodos_pagos (nombre) VALUES (:nombre)";
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            $id_insertado = $this->conn->lastInsertId(); 

            return $id_insertado;

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }
    
}