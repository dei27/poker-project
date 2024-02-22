<?php
require_once('dbModel.php');

class PedidosModel extends BaseModel {

    private $id_pedido;
    private $nombre_cliente;
    private $mesa;
    private $telefono_cliente;
    private $direccion_cliente;
    private $fecha_pedido;
    private $estado_pedido;
    private $total_pedido;
    private $total_restante;

    public function getIdPedido() {
        return $this->id_pedido;
    }

    public function setIdPedido($id_pedido) {
        $this->id_pedido = $id_pedido;
    }

    public function getNombreCliente() {
        return $this->nombre_cliente;
    }

    public function setNombreCliente($nombre_cliente) {
        $this->nombre_cliente = $nombre_cliente;
    }

    public function getTelefonoCliente() {
        return $this->telefono_cliente;
    }

    public function setTelefonoCliente($telefono_cliente) {
        $this->telefono_cliente = $telefono_cliente;
    }

    public function getDireccionCliente() {
        return $this->direccion_cliente;
    }

    public function setDireccionCliente($direccion_cliente) {
        $this->direccion_cliente = $direccion_cliente;
    }

    public function getFechaPedido() {
        return $this->fecha_pedido;
    }

    public function setFechaPedido($fecha_pedido) {
        $this->fecha_pedido = $fecha_pedido;
    }

    public function getEstadoPedido() {
        return $this->estado_pedido;
    }

    public function setEstadoPedido($estado_pedido) {
        $this->estado_pedido = $estado_pedido;
    }

    public function getTotalPedido() {
        return $this->total_pedido;
    }

    public function setTotalPedido($total_pedido) {
        $this->total_pedido = $total_pedido;
    }

    public function setTotalRestante($total_restante) {
        $this->total_restante = $total_restante;
    }

    public function setMesa($mesa) {
        $this->mesa = $mesa;
    }

    public function __construct($nombre_cliente = null,$mesa = null, $telefono_cliente = null,$direccion_cliente = null,$fecha_pedido = null,$estado_pedido = null,$total_pedido = null, $total_restante = null) {
        parent::__construct();
        $this->nombre_cliente = $nombre_cliente;
        $this->mesa = $mesa;
        $this->telefono_cliente = $telefono_cliente;
        $this->direccion_cliente = $direccion_cliente;
        $this->fecha_pedido = $fecha_pedido;
        $this->estado_pedido = $estado_pedido;
        $this->total_pedido = $total_pedido;
        $this->total_restante = $total_restante;
    }

    public function getAllPedidos() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getPedidosById($id_pedido) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos WHERE id_pedido = :id_pedido");
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateTotalPedidoById() {
        try {
            $query = "UPDATE pedidos SET total_pedido = :total_pedido, total_restante = :total_pedido WHERE id_pedido = :id_pedido";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $stmt->bindParam(':total_pedido', $this->total_pedido, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateTotalRestateById() {
        try {
            $query = "UPDATE pedidos SET total_restante = :total_restante WHERE id_pedido = :id_pedido";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $stmt->bindParam(':total_restante', $this->total_restante, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updatePedidoById() {
        try {
            $query = "UPDATE pedidos SET nombre_cliente = :nombre_cliente, mesa = :mesa, telefono_cliente = :telefono_cliente, direccion_cliente = :direccion_cliente WHERE id_pedido = :id_pedido";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
            $stmt->bindParam(':mesa', $this->mesa, PDO::PARAM_INT);
            $stmt->bindParam(':telefono_cliente', $this->telefono_cliente, PDO::PARAM_STR);
            $stmt->bindParam(':direccion_cliente', $this->direccion_cliente, PDO::PARAM_STR);
            // $stmt->bindParam(':estado_pedido', $this->estado_pedido, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function newPedido() {
        try {
            $hora_actual = gmdate("Y-m-d H:i:s", time() - 6 * 3600);
    
            $query = "INSERT INTO pedidos (nombre_cliente, mesa, telefono_cliente, direccion_cliente, fecha_pedido)
            VALUES (:nombre_cliente, :mesa, :telefono_cliente, :direccion_cliente, :fecha_pedido)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
            $stmt->bindParam(':mesa', $this->mesa, PDO::PARAM_INT);
            $stmt->bindParam(':telefono_cliente', $this->telefono_cliente, PDO::PARAM_STR);
            $stmt->bindParam(':direccion_cliente', $this->direccion_cliente, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_pedido', $hora_actual, PDO::PARAM_STR);
            $stmt->execute();
    
            $id_insertado = $this->conn->lastInsertId();
    
            return $id_insertado;
    
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }
    

    public function deletePedidoById() {
    try {
            $query = "DELETE FROM pedidos WHERE id_pedido = :id_pedido";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function newDetallePedidoPlatillo($id_pedido, $id_platillo, $cantidad) {
        try {
            $query = "INSERT INTO detalles_pedido (id_pedido, id_platillo, cantidad) VALUES (:id_pedido, :id_platillo, :cantidad)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->bindParam(':id_platillo', $id_platillo, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }
    
    public function newDetallePedidoBebida($id_pedido, $id_bebida, $cantidad) {
        try {
            $query = "INSERT INTO detalles_pedido (id_pedido, id_bebida, cantidad) VALUES (:id_pedido, :id_bebida, :cantidad)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->bindParam(':id_bebida', $id_bebida, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }

    public function getMontoProductos($id_pedido) {
        try {
            $stmt = $this->conn->prepare("SELECT SUM(COALESCE(r.precio, b.precio_bebida) * cantidad ) AS suma_precios
                FROM detalles_pedido dp
                LEFT JOIN recetas r ON dp.id_platillo = r.id_receta
                LEFT JOIN bebidas b ON dp.id_bebida = b.id_bebida
                WHERE id_pedido = :id_pedido");
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function facturarById($id_pedido) {
        try {
            $query = "UPDATE pedidos SET estado_pedido = 'Cancelado', total_restante = 0 WHERE id_pedido = :id_pedido";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getEstadoPedidoById($id_pedido) {
        try {
            $query = "SELECT estado_pedido FROM pedidos where id_pedido = :id_pedido;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['estado_pedido'];
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    

    public function getPagosPorPersona($id_menor, $id_mayor) {
        try {
            $query = "SELECT pp.*, p.mesa
            FROM pagos_por_persona pp
            INNER JOIN pedidos p ON p.id_pedido = pp.id_factura
            WHERE pp.id_pago BETWEEN :id_menor AND :id_mayor;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_menor', $id_menor, PDO::PARAM_INT);
            $stmt->bindParam(':id_mayor', $id_mayor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }    

     public function deleteAllDetallesByIdPedido() {
        try {
            $query = "DELETE FROM detalles_pedido WHERE id_pedido = :id_pedido";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getCantidadByIdPedidoAndIdProducto($id_pedido, $id_producto, $tipo_producto) {
        try {

            $campo_producto = ($tipo_producto === 'bebida') ? 'id_bebida' : 'id_platillo';

            $query = "SELECT cantidad FROM detalles_pedido WHERE id_pedido = :id_pedido AND $campo_producto = :id_producto;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['cantidad'];
            } else {
                return false;
            }
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateCantidadProductosDetallesPedidos($cantidad, $id_pedido, $id_producto, $tipo_producto) {
        try {
            $campo_producto = ($tipo_producto === 'bebida') ? 'id_bebida' : 'id_platillo';
            
            // Construir la consulta SQL
            $query = "UPDATE detalles_pedido SET cantidad = :cantidad WHERE id_pedido = :id_pedido AND $campo_producto = :id_producto;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function deleteDetallePedidoByIdCantidad($id_pedido) {
        try {  
            $query = "DELETE FROM detalles_pedido WHERE id_pedido = :id_pedido AND cantidad = 0;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }


    public function getAllDetallesPedidosAndPreciosPedidos($id_pedido) {
        try {
            $stmt = $this->conn->prepare("SELECT dp.id_detalle, dp.id_pedido, CONCAT(CASE WHEN r.id_receta IS NOT NULL THEN 'R' ELSE 'B' END, COALESCE(r.id_receta, b.id_bebida)) AS product_id, COALESCE(r.nombre_receta, b.nombre_bebida) AS producto, COALESCE(r.precio, b.precio_bebida) AS precio, dp.cantidad, r.tipo FROM detalles_pedido dp LEFT JOIN recetas r ON dp.id_platillo = r.id_receta LEFT JOIN bebidas b ON dp.id_bebida = b.id_bebida WHERE id_pedido = :id_pedido ORDER BY dp.cantidad ASC;");
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

