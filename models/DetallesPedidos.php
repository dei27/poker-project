<?php
require_once('dbModel.php');

class DetallesPedidos extends BaseModel {

    private $id_detalle;
    private $id_pedido;
    private $id_platillo;
    private $id_bebida;
    private $cantidad;

    public function setIdDetalle($id_detalle) {
        $this->id_detalle = $id_detalle;
    }

    public function setIdPedido($id_pedido) {
        $this->id_pedido = $id_pedido;
    }

    public function setIdPlatillo($id_platillo) {
        $this->id_platillo = $id_platillo;
    }

    public function setIdBebida($id_bebida) {
        $this->id_bebida = $id_bebida;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    public function __construct($id_detalle = null, $id_pedido = null, $id_platillo = null, $id_bebida = null, $cantidad = null) {
        parent::__construct();
        $this->id_detalle = $id_detalle;
        $this->id_pedido = $id_pedido;
        $this->id_platillo = $id_platillo;
        $this->id_bebida = $id_bebida;
        $this->cantidad = $cantidad;
    }

    public function getAllDetallesPedidos($id_pedido) {
        try {
            $stmt = $this->conn->prepare("SELECT dp.id_detalle, dp.id_pedido, COALESCE(r.nombre_receta, b.nombre_bebida) AS producto, dp.cantidad FROM detalles_pedido dp LEFT JOIN recetas r ON dp.id_platillo = r.id_receta LEFT JOIN bebidas b ON dp.id_bebida = b.id_bebida WHERE id_pedido = :id_pedido ORDER BY dp.cantidad ASC;");
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getAllDetallesPedidosAndPrecios($id_pedido) {
        try {
            $stmt = $this->conn->prepare("SELECT dp.id_detalle, dp.id_pedido, CONCAT(CASE WHEN r.id_receta IS NOT NULL THEN 'R' ELSE 'B' END, COALESCE(r.id_receta, b.id_bebida)) AS product_id, COALESCE(r.nombre_receta, b.nombre_bebida) AS producto, COALESCE(r.precio, b.precio_bebida) AS precio, dp.cantidad FROM detalles_pedido dp LEFT JOIN recetas r ON dp.id_platillo = r.id_receta LEFT JOIN bebidas b ON dp.id_bebida = b.id_bebida WHERE id_pedido = :id_pedido ORDER BY dp.cantidad ASC;");
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getAllBebidasAndPlatillos() {
        try {
            $stmt = $this->conn->prepare("SELECT CONCAT('B', id_bebida) AS id, nombre_bebida AS producto, precio_bebida AS precio FROM bebidas
            UNION
            SELECT CONCAT('R', id_receta) AS id, nombre_receta AS producto, precio FROM recetas WHERE principal = 1
            ORDER BY producto;");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
}