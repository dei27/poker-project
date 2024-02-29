<?php
require_once('dbModel.php');

class Producto extends BaseModel {

    private $id;
    private $nombre;
    private $cantidad;
    private $precio;
    private $categoria;
    private $id_unidad;
    private $fecha_ingreso;
    private $fecha_fin;
    private $total_cantidad_producto;


    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
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

    public function setFechaIngreso($fecha_ingreso) {
        $this->fecha_ingreso = $fecha_ingreso;
    }

    public function setFechaFin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }

    public function setTotalCantidadProducto($total_cantidad_producto) {
        $this->total_cantidad_producto = $total_cantidad_producto;
    }


    public function __construct($nombre = null, $cantidad = null, $precio = null, $categoria = null, $id_unidad = null, $total_cantidad_producto = null) {
        parent::__construct();
        $this->nombre = $nombre;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
        $this->categoria = $categoria;
        $this->id_unidad = $id_unidad;
        $this->total_cantidad_producto = $total_cantidad_producto;
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
            $success = $stmt->execute();
            return $success ? true : false;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateProductById() {
        try {
            $query = "UPDATE productos SET nombre = :nombre, cantidad = :cantidad, precio = :precio, id_categoria = :categoria, id_unidad = :id_unidad WHERE id_producto = :id";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
            $stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_INT);
            $stmt->bindParam(':id_unidad', $this->id_unidad, PDO::PARAM_INT);
            $success = $stmt->execute();
            return $success ? true : false;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateProductByIdAndFechaIngreso() {
        try {
            $query = "UPDATE productos SET nombre = :nombre, cantidad = :cantidad, precio = :precio, id_categoria = :categoria, id_unidad = :id_unidad, fecha_ingreso = :fecha_ingreso WHERE id_producto = :id";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
            $stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_INT);
            $stmt->bindParam(':id_unidad', $this->id_unidad, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_ingreso', $this->fecha_ingreso, PDO::PARAM_STR);
            $success = $stmt->execute();
            return $success ? true : false;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function newProduct() {
        try {
            $query = "INSERT INTO productos (nombre, cantidad, precio, id_categoria, id_unidad, total_cantidad_producto) VALUES (:nombre, :cantidad, :precio, :categoria, :id_unidad, :total_cantidad_producto)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
            $stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_INT);
            $stmt->bindParam(':id_unidad', $this->id_unidad, PDO::PARAM_INT);
            $stmt->bindParam(':total_cantidad_producto', $this->total_cantidad_producto, PDO::PARAM_INT);
            $success = $stmt->execute();
            return $success ? true : false;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }

    public function updateInventarioByIdReceta($tabla) {
        try {
            $query = "UPDATE $tabla SET cantidad = :cantidad WHERE id_producto = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_STR);
            $success = $stmt->execute();
            return $success ? true : false;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getAllRecetasCombinasById() {
        try {
            $query = "SELECT id_receta_compuesta, cantidad_receta_compuesta FROM recetas_combinadas WHERE id_receta_principal = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getAllIngredientesRecetaByIdRecta() {
        try {
            $query = "SELECT * FROM recetas_ingredientes WHERE id_receta = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
    
}