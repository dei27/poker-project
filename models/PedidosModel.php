<?php
require_once('dbModel.php');

class PedidosModel extends BaseModel {

    private $id_pedido;
    private $nombre_cliente;
    private $telefono_cliente;
    private $direccion_cliente;
    private $fecha_pedido;
    private $estado_pedido;
    private $total_pedido;

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

    public function __construct($nombre_cliente = null,$telefono_cliente = null,$direccion_cliente = null,$fecha_pedido = null,$estado_pedido = null,$total_pedido = null) {
        parent::__construct();
        $this->nombre_cliente = $nombre_cliente;
        $this->telefono_cliente = $telefono_cliente;
        $this->direccion_cliente = $direccion_cliente;
        $this->fecha_pedido = $fecha_pedido;
        $this->estado_pedido = $estado_pedido;
        $this->total_pedido = $total_pedido;
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

    public function updatePedidoById() {
        try {
            $query = "UPDATE pedidos SET nombre_cliente = :nombre_cliente, telefono_cliente = :telefono_cliente, direccion_cliente = :direccion_cliente, estado_pedido = :estado_pedido WHERE id_pedido = :id_pedido";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
            $stmt->bindParam(':telefono_cliente', $this->telefono_cliente, PDO::PARAM_STR);
            $stmt->bindParam(':direccion_cliente', $this->direccion_cliente, PDO::PARAM_STR);
            $stmt->bindParam(':estado_pedido', $this->estado_pedido, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // public function newPedido() {
    //     try {
    //         $query = "INSERT INTO recetas_ingredientes (id_receta, id_ingrediente, cantidad, unidad_medida) VALUES (:id_receta, :id_ingrediente, :cantidad, :unidad_medida)";
    //         $stmt = $this->conn->prepare($query);
    //         $stmt->bindParam(':id_receta', $this->id_receta, PDO::PARAM_INT);
    //         $stmt->bindParam(':id_ingrediente', $this->id_ingrediente, PDO::PARAM_INT);
    //         $stmt->bindParam(':cantidad', $this->cantidad, PDO::PARAM_STR);
    //         $stmt->bindParam(':unidad_medida', $this->unidad_medida, PDO::PARAM_INT);
    //         $stmt->execute();
    //         return true; 

    //     } catch (PDOException $e) {
    //         die("Error: " . $e->getMessage());
    //         return false;
    //     }
    // }

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
}
