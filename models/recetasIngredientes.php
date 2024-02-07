<?php
require_once('dbModel.php');

class RecetaIngredienteModel extends BaseModel {

    private $id_receta;
    private $id_ingrediente;
    private $cantidad;
    private $unidad_medida;


    public function setIdReceta($id_receta) {
        $this->id_receta = $id_receta;
    }

    public function setIdIngrediente($id_ingrediente) {
        $this->id_ingrediente = $id_ingrediente;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    public function setUnidadMedida($unidad_medida) {
        $this->unidad_medida = $unidad_medida;
    }



    public function __construct($id_receta = null,$id_ingrediente = null,$cantidad = null,$unidad_medida = null) {
        parent::__construct();
        $this->id_receta = $id_receta;
        $this->id_ingrediente = $id_ingrediente;
        $this->cantidad = $cantidad;
        $this->unidad_medida = $unidad_medida;
    }

    public function getIngredientesDeReceta($idReceta) {
        try {
            $stmt = $this->conn->prepare("SELECT ri.id_receta, ri.id_ingrediente, ri.cantidad, ri.unidad_medida, p.nombre, um.nombre_unidad
                FROM recetas_ingredientes ri
                INNER JOIN productos p ON p.id_producto = ri.id_ingrediente
                INNER JOIN unidades_medidas um ON um.id_unidad = ri.unidad_medida
                WHERE id_receta = :id_receta");
            $stmt->bindParam(':id_receta', $idReceta, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // public function getRecetasByCondicion($campo, $valor) {
    //     try {
    //         $query = "SELECT * FROM recetas WHERE $campo = :valor";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bindParam(':valor', $valor, PDO::PARAM_INT);  // Enlazar el valor
    //         $stmt->execute();
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (PDOException $e) {
    //         die("Error: " . $e->getMessage());
    //     }
    // }
    
    

    // public function newReceta() {
    //     try {
    //         $query = "INSERT INTO recetas (nombre_receta, tiempo_preparacion, principal, complementaria, especial ) VALUES (:nombre_receta,:tiempo_preparacion,:principal,:complementaria,:especial)";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bindParam(':nombre_receta', $this->nombre_receta, PDO::PARAM_STR);
    //         $stmt->bindParam(':tiempo_preparacion', $this->tiempo_preparacion, PDO::PARAM_INT);
    //         $stmt->bindParam(':principal', $this->principal, PDO::PARAM_INT);
    //         $stmt->bindParam(':complementaria', $this->complementaria, PDO::PARAM_INT);
    //         $stmt->bindParam(':especial', $this->especial, PDO::PARAM_INT);
    //         $stmt->execute();
    //         return true; 

    //     } catch (PDOException $e) {
    //         die("Error: " . $e->getMessage());
    //         return false;
    //     }
    // }

    // public function deleteRecipeById() {
    //     try {
    //         $query = "DELETE FROM recetas WHERE id_receta = :id_receta";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bindParam(':id_receta', $this->id_receta, PDO::PARAM_INT);
    //         $stmt->execute();
    //         return true; 
    //     } catch (PDOException $e) {
    //         die("Error: " . $e->getMessage());
    //     }
    // }

    // public function updateRecipeById() {
    //     try {
    //         $query = "UPDATE recetas SET nombre_receta = :nombre_receta, tiempo_preparacion = :tiempo_preparacion, principal = :principal, complementaria = :complementaria, especial = :especial WHERE id_receta = :id_receta";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bindParam(':id_receta', $this->id_receta, PDO::PARAM_INT);
    //         $stmt->bindParam(':nombre_receta', $this->nombre_receta, PDO::PARAM_STR);
    //         $stmt->bindParam(':tiempo_preparacion', $this->tiempo_preparacion, PDO::PARAM_INT);
    //         $stmt->bindParam(':principal', $this->principal, PDO::PARAM_INT);
    //         $stmt->bindParam(':complementaria', $this->complementaria, PDO::PARAM_INT);
    //         $stmt->bindParam(':especial', $this->especial, PDO::PARAM_INT);
    //         $stmt->execute();
    //         return true;
    //     } catch (PDOException $e) {
    //         die("Error: " . $e->getMessage());
    //     }
    // }
}
