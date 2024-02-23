<?php
require_once('dbModel.php');

class UnidadMedida extends BaseModel {

    private $id;
    private $nombre_unidad;


    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre_unidad) {
        $this->nombre_unidad = $nombre_unidad;
    }


    public function __construct($nombre_unidad = null) {
        parent::__construct();
        $this->nombre_unidad = $nombre_unidad;
    }

    public function getAllUnidadesMedidas() {
        try {
            $query = "SELECT * FROM unidades_medidas ORDER BY nombre_unidad DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getNombreUnidadByIdUnidad($id_unidad) {
        try {
            $query = "SELECT nombre_unidad FROM unidades_medidas WHERE id_unidad = :id_unidad LIMIT 1;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_unidad', $id_unidad, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }


    public function newUnidadMedida() {
        try {
            $query = "INSERT INTO unidades_medidas (nombre_unidad) VALUES (:nombre_unidad)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $this->nombre_unidad, PDO::PARAM_STR);
            $stmt->execute();
            return true; 

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }
    
}