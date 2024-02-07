<?php
require_once('dbModel.php');

class Producto extends BaseModel {

    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $categoria;
    private $id_unidad;


    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    public function setUnidad($id_unidad) {
        $this->id_unidad = $id_unidad;
    }


    public function __construct($nombre = null, $descripcion = null, $precio = null, $categoria = null, $id_unidad = null) {
        parent::__construct();
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->categoria = $categoria;
        $this->id_unidad = $id_unidad;
    }

    public function getAllProdcuts() {
        try {
            $query = "SELECT * FROM productos";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getAllProductsNotIn($id_receta) {
        try {
            $query = "SELECT p.*, cp.nombre_categoria, um.nombre_unidad
            FROM productos p
            LEFT JOIN recetas_ingredientes ri ON p.id_producto = ri.id_ingrediente AND ri.id_receta = :id_receta
            INNER JOIN unidades_medidas um
            ON p.id_unidad = um.id_unidad
            INNER JOIN categorias_productos cp
            ON p.id_categoria = cp.id_categoria
            WHERE ri.id_receta IS NULL;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_receta', $id_receta, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    

    public function deleteProductById($id) {
        try {
            $query = "DELETE FROM productos WHERE id_producto = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateProductById() {
        try {
            $query = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, id_categoria = :categoria, id_unidad = :id_unidad WHERE id_producto = :id";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
            $stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_INT);
            $stmt->bindParam(':id_unidad', $this->id_unidad, PDO::PARAM_INT);
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function newProduct() {
        try {
            $query = "INSERT INTO productos (nombre, descripcion, precio, id_categoria, id_unidad) VALUES (:nombre, :descripcion, :precio, :categoria, :id_unidad)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $this->descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
            $stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_INT);
            $stmt->bindParam(':id_unidad', $this->id_unidad, PDO::PARAM_INT);
            $stmt->execute();
            return true; 

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }
    
}