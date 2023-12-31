<?php
require_once('dbModel.php');

class RecetaModel extends BaseModel {

    private $id_receta;
    private $nombre_receta;
    private $instrucciones;
    private $tiempo_preparacion;
    private $principal;
    private $complementaria;
    private $especial;


    public function setId($id_receta) {
        $this->id_receta = $id_receta;
    }

    public function setNombre($nombre_receta) {
        $this->nombre_receta = $nombre_receta;
    }

    public function setInstrucciones($instrucciones) {
        $this->instrucciones = $instrucciones;
    }

    public function setTiempo($tiempo_preparacion) {
        $this->tiempo_preparacion = $tiempo_preparacion;
    }

    public function setPrincipal($principal) {
        $this->principal = $principal;
    }

    public function setComplementaria($complementaria) {
        $this->complementaria = $complementaria;
    }

    public function setEspecial($especial) {
        $this->especial = $especial;
    }


    public function __construct($nombre_receta = null,$instrucciones = null,$tiempo_preparacion = null,$principal = null,$complementaria = null,$especial = null) {
        parent::__construct();
        $this->nombre_receta = $nombre_receta;
        $this->instrucciones = $instrucciones;
        $this->tiempo_preparacion = $tiempo_preparacion;
        $this->principal = $principal;
        $this->complementaria = $complementaria;
        $this->especial = $especial;
    }

    public function getAllRecetas() {
        try {
            $query = "SELECT * FROM recetas";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getRecetasByCondicion($campo, $valor) {
        try {
            $query = "SELECT * FROM recetas WHERE $campo = :valor";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':valor', $valor, PDO::PARAM_INT);  // Enlazar el valor
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
    

    public function newReceta() {
        try {
            $query = "INSERT INTO recetas (nombre_receta, tiempo_preparacion, principal, complementaria, especial ) VALUES (:nombre_receta,:tiempo_preparacion,:principal,:complementaria,:especial)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_receta', $this->nombre_receta, PDO::PARAM_STR);
            $stmt->bindParam(':tiempo_preparacion', $this->tiempo_preparacion, PDO::PARAM_INT);
            $stmt->bindParam(':principal', $this->principal, PDO::PARAM_INT);
            $stmt->bindParam(':complementaria', $this->complementaria, PDO::PARAM_INT);
            $stmt->bindParam(':especial', $this->especial, PDO::PARAM_INT);
            $stmt->execute();
            return true; 

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteRecipeById() {
        try {
            $query = "DELETE FROM recetas WHERE id_receta = :id_receta";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_receta', $this->id_receta, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateRecipeById() {
        try {
            $query = "UPDATE recetas SET nombre_receta = :nombre_receta, tiempo_preparacion = :tiempo_preparacion, principal = :principal, complementaria = :complementaria, especial = :especial WHERE id_receta = :id_receta";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_receta', $this->id_receta, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_receta', $this->nombre_receta, PDO::PARAM_STR);
            $stmt->bindParam(':tiempo_preparacion', $this->tiempo_preparacion, PDO::PARAM_INT);
            $stmt->bindParam(':principal', $this->principal, PDO::PARAM_INT);
            $stmt->bindParam(':complementaria', $this->complementaria, PDO::PARAM_INT);
            $stmt->bindParam(':especial', $this->especial, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
