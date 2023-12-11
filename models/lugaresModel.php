<?php
require_once('dbModel.php');

class Lugar extends BaseModel {

    protected $id;
    protected $nombre;
    protected $telefono;
    protected $ciudad;
    protected $direccion;

    public function __construct($nombre = null, $telefono = null, $ciudad = null, $direccion = null) {
        parent::__construct();
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->ciudad = $ciudad;
        $this->direccion = $direccion;
    }

    public function getAllPlaces() {
        try {
            $query = "SELECT * FROM lugares";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getPlaceById($id) {
        try {
            $query = "SELECT * FROM lugares WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function deletePlaceById($id) {
        try {
            $query = "DELETE FROM lugares WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
}