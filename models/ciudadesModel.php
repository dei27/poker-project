<?php
require_once('dbModel.php');

class Ciudades extends BaseModel{
    protected $id;
    protected $nombre;

    public function __construct($nombre = null) {
        parent::__construct();
        $this->nombre = $nombre;
    }

    public function getAllCities() {
        try {
            $query = "SELECT * FROM ciudades";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getCityById($id) {
        try {
            $query = "SELECT * FROM ciudades WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}