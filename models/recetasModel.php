<?php
require_once('dbModel.php');

class RecetaModel extends BaseModel {

    private $id_receta;
    private $nombre_receta;
    private $precio;
    private $tiempo_preparacion;
    private $principal;
    private $complementaria;
    private $especial;
    private $tipo;


    public function setId($id_receta) {
        $this->id_receta = $id_receta;
    }

    public function setNombre($nombre_receta) {
        $this->nombre_receta = $nombre_receta;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
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

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }


    public function __construct($nombre_receta = null, $precio = null, $tiempo_preparacion = null,$principal = null,$complementaria = null,$especial = null, $tipo = null) {
        parent::__construct();
        $this->nombre_receta = $nombre_receta;
        $this->precio = $precio;
        $this->tiempo_preparacion = $tiempo_preparacion;
        $this->principal = $principal;
        $this->complementaria = $complementaria;
        $this->especial = $especial;
        $this->tipo = $tipo;
    }

    public function getAllRecetas() {
        try {
            $query = "SELECT r.*, tr.nombre_tipo
            FROM recetas r
            INNER JOIN tipos_recetas tr ON r.tipo = tr.id_tipo;";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getAllRecetasComplementarias() {
        try {
            $query = "SELECT r.*, tr.nombre_tipo
            FROM recetas r
            INNER JOIN tipos_recetas tr ON r.tipo = tr.id_tipo 
            WHERE r.complementaria = 1
            AND NOT EXISTS (
                SELECT 1 FROM recetas_combinadas rc
                WHERE rc.id_receta_compuesta = r.id_receta
            )
            AND r.id_receta != :id_receta;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_receta', $this->id_receta, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }



    

    public function getRecetasByCondicion($campo, $valor) {
        try {
            $query = "SELECT r.*, tr.nombre_tipo
            FROM recetas r
            INNER JOIN tipos_recetas tr ON r.tipo = tr.id_tipo 
            WHERE $campo = :valor";
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
            $query = "INSERT INTO recetas (nombre_receta, precio, tiempo_preparacion, principal, complementaria, especial, tipo ) VALUES (:nombre_receta, :precio, :tiempo_preparacion,:principal,:complementaria,:especial, :tipo)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_receta', $this->nombre_receta, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
            $stmt->bindParam(':tiempo_preparacion', $this->tiempo_preparacion, PDO::PARAM_INT);
            $stmt->bindParam(':principal', $this->principal, PDO::PARAM_INT);
            $stmt->bindParam(':complementaria', $this->complementaria, PDO::PARAM_INT);
            $stmt->bindParam(':especial', $this->especial, PDO::PARAM_INT);
            $stmt->bindParam(':tipo', $this->tipo, PDO::PARAM_INT);
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
            $query = "UPDATE recetas SET nombre_receta = :nombre_receta, precio = :precio, tiempo_preparacion = :tiempo_preparacion, principal = :principal, complementaria = :complementaria, especial = :especial, tipo = :tipo WHERE id_receta = :id_receta";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_receta', $this->id_receta, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_receta', $this->nombre_receta, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
            $stmt->bindParam(':tiempo_preparacion', $this->tiempo_preparacion, PDO::PARAM_INT);
            $stmt->bindParam(':principal', $this->principal, PDO::PARAM_INT);
            $stmt->bindParam(':complementaria', $this->complementaria, PDO::PARAM_INT);
            $stmt->bindParam(':especial', $this->especial, PDO::PARAM_INT);
            $stmt->bindParam(':tipo', $this->tipo, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
