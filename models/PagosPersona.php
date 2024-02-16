<?php
require_once('dbModel.php');

class PagosPersona extends BaseModel {

    private $id_pago;
    private $id_factura;
    private $nombre;
    private $telefono;
    private $tipo_producto;
    private $precio;
    private $cantidad;
    private $fecha_pago;

    public function setIdPago($id_pago) {
        $this->id_pago = $id_pago;
    }

    // Setter para id_factura
    public function setIdFactura($id_factura) {
        $this->id_factura = $id_factura;
    }

    // Setter para nombre
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    // Setter para telefono
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    // Setter para tipo_producto
    public function setTipoProducto($tipo_producto) {
        $this->tipo_producto = $tipo_producto;
    }

    // Setter para precio
    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    // Setter para cantidad
    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    // Setter para fecha_pago
    public function setFechaPago($fecha_pago) {
        $this->fecha_pago = $fecha_pago;
    }

    public function __construct($id_pago = null, $id_factura = null, $nombre = null, $telefono = null, $tipo_producto = null, $precio = null, $cantidad = null, $fecha_pago = null) {
        parent::__construct();
        $this->id_pago = $id_pago;
        $this->id_factura = $id_factura;
        $this->nombre = $nombre;
        $this->telefono = $telefono;
        $this->tipo_producto = $tipo_producto;
        $this->precio = $precio;
        $this->cantidad = $cantidad;
        $this->fecha_pago = $fecha_pago;
    }

    public function getAllPagosPersona() {
        try {
            $query = "SELECT * FROM pagos_por_persona";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function deleteProductById($id) {
        try {
            $query = "DELETE FROM pagos_por_persona WHERE id_factura = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // public function updateProductById() {
    //     try {
    //         $query = "UPDATE productos SET nombre = :nombre, cantidad = :cantidad, precio = :precio, id_categoria = :categoria, id_unidad = :id_unidad WHERE id_producto = :id";
    //         $stmt = $this->conn->prepare($query);
            
    //         $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    //         $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
    //         $stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_STR);
    //         $stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
    //         $stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_INT);
    //         $stmt->bindParam(':id_unidad', $this->id_unidad, PDO::PARAM_INT);
    //         $stmt->execute();
            
    //         return true;
    //     } catch (PDOException $e) {
    //         die("Error: " . $e->getMessage());
    //     }
    // }

    public function nuevoPagoFactura() {
        try {
            $hora_actual = gmdate("Y-m-d H:i:s", time() - 6 * 3600);

            $query = "INSERT INTO pagos_por_persona (id_factura, nombre, telefono, tipo_producto, precio, cantidad, fecha_pago) VALUES (:id_factura, :nombre, :telefono, :tipo_producto, :precio, :cantidad, :fecha_pago)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_factura', $this->id_factura, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $this->telefono, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_producto', $this->tipo_producto, PDO::PARAM_INT);
            $stmt->bindParam(':precio', $this->precio, PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_pago', $hora_actual, PDO::PARAM_STR);
            $stmt->execute();
            return true; 

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }
    
}