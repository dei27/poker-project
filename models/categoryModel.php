<?php
require_once('dbModel.php');

class Category extends BaseModel {

    private $id;
    private $nombre_categoria;

    public function setId($id){
        $this->id = $id;
    }

    public function setNombreCategoria($nombre_categoria){
        $this->nombre_categoria = $nombre_categoria;
    }


    public function __construct($nombre_categoria = null) {
        parent::__construct();
        $this->nombre_categoria = $nombre_categoria;
    }

    public function getAllCategories() {
        try {
            $query = "SELECT * FROM categorias_productos";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getCategoryById($id) {
        try {
            $query = "SELECT nombre_categoria FROM categorias_productos WHERE id_categoria = :id LIMIT 1;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    


    public function deleteCategoryById($id) {
        try {
            $query = "DELETE FROM categorias_productos WHERE id_categoria = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateCategoryById() {
        try {
            $query = "UPDATE categorias_productos SET nombre_categoria = :nombre_categoria WHERE id_categoria = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_categoria', $this->nombre_categoria, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Error updating player: " . $e->getMessage());
        }
    }

    public function newCategory() {
        try {
            $query = "INSERT INTO categorias_productos (nombre_categoria) VALUES (:nombre_categoria)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_categoria', $this->nombre_categoria, PDO::PARAM_STR);
            $stmt->execute();
            return true; 

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }
    
    
}