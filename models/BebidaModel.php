<?php
require_once('dbModel.php');

class BebidaModel extends BaseModel {

    private $id_bebida;
    private $nombre_bebida;
    private $precio_bebida;

    public function setId($id_bebida) {
        $this->id_bebida = $id_bebida;
    }

    public function setNombre($nombre_bebida) {
        $this->nombre_bebida = $nombre_bebida;
    }

    public function setPrecio($precio_bebida) {
        $this->precio_bebida = $precio_bebida;
    }


    public function __construct($nombre_bebida = null, $precio_bebida = null) {
        parent::__construct();
        $this->nombre_bebida = $nombre_bebida;
        $this->precio_bebida = $precio_bebida;
    }

    public function getAllBebidas() {
        try {
            $query = "SELECT * FROM bebidas";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function deleteBebidaById() {
        try {
            $query = "DELETE FROM bebidas WHERE id_bebida = :id_bebida";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_bebida', $this->id_bebida, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateBebidaById() {
        try {
            $query = "UPDATE bebidas SET nombre_bebida = :nombre_bebida, precio_bebida = :precio_bebida WHERE id_bebida = :id_bebida";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_bebida', $this->id_bebida, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_bebida', $this->nombre_bebida, PDO::PARAM_STR);
            $stmt->bindParam(':precio_bebida', $this->precio_bebida, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function newBebida() {
        try {
            $query = "INSERT INTO bebidas (nombre_bebida, precio_bebida) VALUES (:nombre_bebida, :precio_bebida)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $this->nombre_bebida, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $this->precio_bebida, PDO::PARAM_STR);
            $stmt->execute();
            return true; 

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }
    
}