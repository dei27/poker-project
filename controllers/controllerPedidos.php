<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/PedidosModel.php');
include(__DIR__ . '/../models/PagosPersona.php');

function getAllPedidos() {
    $pedidosModel = new PedidosModel();
    $pedidos = $pedidosModel->getAllPedidos();
    return json_encode($pedidos);
}

function getPedidoById($id_pedido){
    $pedidosModel = new PedidosModel();
    $pedido = $pedidosModel->getPedidosById($id_pedido);
    return $pedido; 
}

function facturarPedidoById($id_pedido){
    $pedidosModel = new PedidosModel();
    $pedido = $pedidosModel->facturarById($id_pedido);
    return $pedido; 
}


if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $pedidosModel = new PedidosModel();
    $pedidosModel->setIdPedido($id);
    $result = $pedidosModel->deletePedidoById();

    if($result){
        header("Location: ../views/ordenes.php?deletePedido=1");
        exit();
    }else{
        header("Location: ../views/ordenes.php?deletePedido=0");
        exit();
    }
    
}

if (isset($_POST['id'], $_POST['action']) && $_POST['action'] === 'edit') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nombre = isset($_POST['nombreCliente']) ? htmlspecialchars($_POST['nombreCliente'], ENT_QUOTES, 'UTF-8') : "Sin nombre";
    $telefono = isset($_POST['telefonoCliente']) ? htmlspecialchars($_POST['telefonoCliente'], ENT_QUOTES, 'UTF-8') : "Sin telefono";
    $direccion = isset($_POST['direccionCliente']) ? htmlspecialchars($_POST['direccionCliente'], ENT_QUOTES, 'UTF-8') : "Sin dirección";
    // $estado = isset($_POST['estadoPedido']) ? htmlspecialchars($_POST['estadoPedido'], ENT_QUOTES, 'UTF-8') : "Sin estado";
    $mesaCliente = isset($_POST['mesaCliente']) ? htmlspecialchars($_POST['mesaCliente'], ENT_QUOTES, 'UTF-8') : "0"; 

    if ($id !== null && !empty($id)) {
        $pedidosModel = new PedidosModel();
        $pedidosModel->setIdPedido($id);
        $pedidosModel->setNombreCliente($nombre);
        $pedidosModel->setMesa($mesaCliente);
        $pedidosModel->setTelefonoCliente($telefono);
        $pedidosModel->setDireccionCliente($direccion);
        // $pedidosModel->setEstadoPedido($estado);

        $result = $pedidosModel->updatePedidoById();

        if($result){
            header("Location: ../views/ordenes.php?updatedPedido=1");
            exit();
        }else{
            header("Location: ../views/ordenes.php?updatedPedido=0");
            exit();
        }
    }else{        
        header("Location: ../views/ordenes.php?updatedPedido=0");
        exit();
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'addOrden') {
    $nombre = isset($_POST['nombreClienteOrden']) ? htmlspecialchars($_POST['nombreClienteOrden'], ENT_QUOTES, 'UTF-8') : "Sin nombre"; 
    $mesa = isset($_POST['mesaOrden']) ? htmlspecialchars($_POST['mesaOrden'], ENT_QUOTES, 'UTF-8') : "1"; 
    $telefono = isset($_POST['telefonoClienteOrden']) ? htmlspecialchars($_POST['telefonoClienteOrden'], ENT_QUOTES, 'UTF-8') : "Sin teléfono";   
    $servicio = isset($_POST['servicioOrden']) ? htmlspecialchars($_POST['servicioOrden'], ENT_QUOTES, 'UTF-8') : "10";
    // $iva = isset($_POST['ivaOrden']) ? htmlspecialchars($_POST['ivaOrden'], ENT_QUOTES, 'UTF-8') : "13"; 
    $direccion = isset($_POST['direccionClienteOrden']) ? htmlspecialchars($_POST['direccionClienteOrden'], ENT_QUOTES, 'UTF-8') : "Sin dirección"; 
    $inputs = $_POST['cantidad'];
    $bebidas = [];
    $platillos = [];
    
    if(!empty($inputs)){
        $pedidosModel = new PedidosModel();
        $pedidosModel->setNombreCliente($nombre);
        $pedidosModel->setMesa($mesa);
        $pedidosModel->setTelefonoCliente($telefono);
        $pedidosModel->setDireccionCliente($direccion);
        $insertedId = $pedidosModel->newPedido();
        $insertedBebidas = false;
        $insertedPlatillos = false;
        $montoTotal = 0;
        

        if($insertedId != 0){
            
            foreach ($inputs as $clave => $valor) {
                if (strpos($clave, 'B') === 0) {
                    $bebidas[substr($clave, 1)] = $valor;
                } elseif (strpos($clave, 'R') === 0) {
                    $platillos[substr($clave, 1)] = $valor;
                }
            }

            if(!empty($bebidas)){
                
                foreach ($bebidas as $id_bebida => $cantidad) {
                    if (!empty($cantidad)) {
                        $insertedBebidas = $pedidosModel->newDetallePedidoBebida($insertedId, $id_bebida, $cantidad);
                    }
                }
            }

            if(!empty($platillos)){

                foreach ($platillos as $id_platillo  => $cantidad) {
                    if (!empty($cantidad)) {
                        $insertedPlatillos = $pedidosModel->newDetallePedidoPlatillo($insertedId, $id_platillo, $cantidad);
                    }
                }
            }

            if($insertedPlatillos || $insertedBebidas){

                $montoTotal = $pedidosModel->getMontoProductos($insertedId);

                $montoTotalConServicio = $montoTotal + ($montoTotal * $servicio / 100);

                $pedidosUpdate = new PedidosModel();
                $pedidosUpdate->setIdPedido($insertedId);
                $pedidosUpdate->setTotalPedido($montoTotalConServicio);
                $updateMonto = $pedidosUpdate->updateTotalPedidoById();

                if($updateMonto){
                    header("Location: ../views/ordenes.php?orderAdd=1");
                    exit();
                }else{
                    header("Location: ../views/ordenes.php?orderAdd=0");
                    exit();
                }

                
                
            }else{
                header("Location: ../views/ordenes.php?orderAdd=0");
                exit();
            }
        }
        
    }else{
        header("Location: ../views/nuevaOrden.php?emptyProducts=0");
        exit();
    }
    
}

if (isset($_POST['action']) && $_POST['action'] === 'addOrdenExpress') {
    $nombre = isset($_POST['nombreClienteOrden']) ? htmlspecialchars($_POST['nombreClienteOrden'], ENT_QUOTES, 'UTF-8') : "Sin nombre"; 
    $telefono = isset($_POST['telefonoClienteOrden']) ? htmlspecialchars($_POST['telefonoClienteOrden'], ENT_QUOTES, 'UTF-8') : "Sin teléfono";   
    $direccion = isset($_POST['direccionClienteOrden']) ? htmlspecialchars($_POST['direccionClienteOrden'], ENT_QUOTES, 'UTF-8') : "Sin dirección"; 
    $inputs = $_POST['cantidad'];
    $bebidas = [];
    $platillos = [];
    
    if(!empty($inputs)){
        $pedidosModel = new PedidosModel();
        $pedidosModel->setNombreCliente($nombre);
        $pedidosModel->setTelefonoCliente($telefono);
        $pedidosModel->setMesa(null);
        $pedidosModel->setDireccionCliente($direccion);
        $insertedId = $pedidosModel->newPedido();
        $insertedBebidas = false;
        $insertedPlatillos = false;
        $montoTotal = 0;
        

        if($insertedId != 0){
            
            foreach ($inputs as $clave => $valor) {
                if (strpos($clave, 'B') === 0) {
                    $bebidas[substr($clave, 1)] = $valor;
                } elseif (strpos($clave, 'R') === 0) {
                    $platillos[substr($clave, 1)] = $valor;
                }
            }

            if(!empty($bebidas)){
                
                foreach ($bebidas as $id_bebida => $cantidad) {
                    if (!empty($cantidad)) {
                        $insertedBebidas = $pedidosModel->newDetallePedidoBebida($insertedId, $id_bebida, $cantidad);
                    }
                }
            }

            if(!empty($platillos)){

                foreach ($platillos as $id_platillo  => $cantidad) {
                    if (!empty($cantidad)) {
                        $insertedPlatillos = $pedidosModel->newDetallePedidoPlatillo($insertedId, $id_platillo, $cantidad);
                    }
                }
            }

            if($insertedPlatillos || $insertedBebidas){

                $montoTotal = $pedidosModel->getMontoProductos($insertedId);
                $pedidosUpdate = new PedidosModel();
                $pedidosUpdate->setIdPedido($insertedId);
                $pedidosUpdate->setTotalPedido($montoTotal);
                $updateMonto = $pedidosUpdate->updateTotalPedidoById();

                if($updateMonto){
                    header("Location: ../views/ordenes.php?orderAdd=1");
                    exit();
                }else{
                    header("Location: ../views/ordenes.php?orderAdd=0");
                    exit();
                }

                
                
            }else{
                header("Location: ../views/ordenes.php?orderAdd=0");
                exit();
            }
        }
        
    }else{
        header("Location: ../views/nuevaOrdenExpress.php?emptyProducts=0");
        exit();
    }  
}

if (isset($_POST['action']) && $_POST['action'] === 'updateOrden') {
    $id_orden = filter_input(INPUT_POST, 'idOrden', FILTER_SANITIZE_NUMBER_INT);
    $nombre = isset($_POST['nombreClienteUpdate']) ? htmlspecialchars($_POST['nombreClienteUpdate'], ENT_QUOTES, 'UTF-8') : "Sin nombre"; 
    $mesaOrdenUpdate = isset($_POST['mesaOrdenUpdate']) ? htmlspecialchars($_POST['mesaOrdenUpdate'], ENT_QUOTES, 'UTF-8') : null;
    $telefono = isset($_POST['telefonoClienteUpdate']) ? htmlspecialchars($_POST['telefonoClienteUpdate'], ENT_QUOTES, 'UTF-8') : "Sin teléfono";   
    $servicio = isset($_POST['servicioOrdenUpdate']) ? htmlspecialchars($_POST['servicioOrdenUpdate'], ENT_QUOTES, 'UTF-8') : "0";
    // $iva = isset($_POST['ivaOrdenUpdate']) ? htmlspecialchars($_POST['ivaOrdenUpdate'], ENT_QUOTES, 'UTF-8') : "13"; 
    $direccion = isset($_POST['direccionClienteUpdate']) ? (trim($_POST['direccionClienteUpdate']) !== '' ? htmlspecialchars($_POST['direccionClienteUpdate'], ENT_QUOTES, 'UTF-8') : "Sin dirección") : "Sin dirección";
    $inputs = $_POST['cantidad'];
    $bebidas = [];
    $platillos = [];
    
    if(!empty($inputs)){
        $pedidosModel = new PedidosModel();
        $pedidosModel->setIdPedido($id_orden);
        $pedidosModel->setNombreCliente($nombre);
        $pedidosModel->setMesa($mesaOrdenUpdate);
        $pedidosModel->setTelefonoCliente($telefono);
        $pedidosModel->setDireccionCliente($direccion);
        $pedidosModel->setEstadoPedido("Pendiente");

        $updatedPedido = $pedidosModel->updatePedidoById();

        $deletedPedidos = $pedidosModel->deleteAllDetallesByIdPedido();
        
        $insertedBebidas = false;
        $insertedPlatillos = false;
        $montoTotal = 0;
        
        if($updatedPedido && $deletedPedidos){
            
            foreach ($inputs as $clave => $valor) {
                if (strpos($clave, 'B') === 0) {
                    $bebidas[substr($clave, 1)] = $valor;
                } elseif (strpos($clave, 'R') === 0) {
                    $platillos[substr($clave, 1)] = $valor;
                }
            }

            if(!empty($bebidas)){
                
                foreach ($bebidas as $id_bebida => $cantidad) {
                    if (!empty($cantidad)) {
                        $insertedBebidas = $pedidosModel->newDetallePedidoBebida($id_orden, $id_bebida, $cantidad);
                    }
                }
            }

            if(!empty($platillos)){

                foreach ($platillos as $id_platillo  => $cantidad) {
                    if (!empty($cantidad)) {
                        $insertedPlatillos = $pedidosModel->newDetallePedidoPlatillo($id_orden, $id_platillo, $cantidad);
                    }
                }
            }

            if($insertedPlatillos || $insertedBebidas){

                $montoTotal = $pedidosModel->getMontoProductos($id_orden);

                $montoTotalConServicio = $montoTotal + ($montoTotal * $servicio / 100);

                $pedidosUpdate = new PedidosModel();
                $pedidosUpdate->setIdPedido($id_orden);
                $pedidosUpdate->setTotalPedido($montoTotalConServicio);
                $updateMonto = $pedidosUpdate->updateTotalPedidoById();

                if($updateMonto){
                    header("Location: ../views/ordenes.php?updatedOrden=1");
                    exit();
                }else{
                    header("Location: ../views/ordenes.php?updatedOrden=0");
                    exit();
                }

                
                
            }else{
                header("Location: ../views/ordenes.php?updatedOrden=0");
                exit();
            }
        }else{
            header("Location: ../views/ordenes.php?updateData=0");
            exit();
        }
        
    }else{
        header("Location: ../views/actualizarOrden.php?idPedido=$id_orden&emptyProducts=0");
        exit();
    }
    
}


if (isset($_POST['action']) && $_POST['action'] === 'procesarPago') {
    $id_orden = filter_input(INPUT_POST, 'idOrden', FILTER_SANITIZE_NUMBER_INT);
    $nombre = isset($_POST['nombreClienteProcesarSeparado']) ? htmlspecialchars($_POST['nombreClienteProcesarSeparado'], ENT_QUOTES, 'UTF-8') : "Sin nombre"; 
    $telefono = isset($_POST['telefonoProcesarSeparado']) ? htmlspecialchars($_POST['telefonoProcesarSeparado'], ENT_QUOTES, 'UTF-8') : "Sin teléfono"; 
    $mesa = isset($_POST['mesaOrdenProcesarSeparado']) ? htmlspecialchars($_POST['mesaOrdenProcesarSeparado'], ENT_QUOTES, 'UTF-8') : "null"; 
    $cantidades = $_POST['cantidadSeparado'];
    $bebidas = [];
    $platillos = [];
    $idsCreados = [];
    $resultUpdatedBebidas = false;
    $resultUpdatedPlatillos = false;
    
    if(!empty($cantidades)){

        foreach ($cantidades as $clave => $valor) {
            $id_producto = substr($clave, 1);
            $precio = $_POST['preciosSeparado'][$clave];
            $cantidadPagar = $_POST['cantidadPagar'][$clave];
            $nombreProducto = $_POST['nombresProducto'][$clave];

            if (strpos($clave, 'B') === 0) {
                $bebidas[$id_producto] = array(
                    'cantidad' => $valor,
                    'precio' => $precio,
                    'cantidadPagar' => $cantidadPagar,
                    'nombre_producto' => $nombreProducto,
                    'id_producto' => $id_producto,
                );
            } elseif (strpos($clave, 'R') === 0) {
                $platillos[$id_producto] = array(
                    'cantidad' => $valor,
                    'precio' => $precio,
                    'cantidadPagar' => $cantidadPagar,
                    'nombre_producto' => $nombreProducto,
                    'id_producto' => $id_producto,
                );
            }
        }

        if (!empty($bebidas)) {
            foreach ($bebidas as $id_producto => $datos_producto) {

                $cantidad = intval($datos_producto['cantidadPagar']);
                $pedido = new PedidosModel();
                $tipo = 'bebida';
                $cantidadExistente = intval($pedido->getCantidadByIdPedidoAndIdProducto($id_orden, $id_producto, $tipo));

                if($cantidad !== 0 && $cantidad <= $cantidadExistente){
                    $pagosModel = new PagosPersona();
                    $pagosModel->setIdFactura($id_orden);
                    $pagosModel->setNombre($nombre);
                    $pagosModel->setTelefono($telefono);

                    $precio = $datos_producto['precio'];
                    $productoNombre = $datos_producto['nombre_producto'];
                    $id__producto = $datos_producto['id_producto'];
                    
                    $pagosModel->setTipoProducto(1);
                    $pagosModel->setPrecio($precio);
                    $pagosModel->setCantidad($cantidad); 
                    $pagosModel->setNombreProducto($productoNombre); 
                    $pagosModel->setIdProducto($id__producto); 
                    $insertedId = $pagosModel->nuevoPagoFactura();

                    if($insertedId > 0 && $cantidadExistente > 0){
                        $cantidadRestante = $cantidadExistente - $cantidad;
                        $resultUpdatedBebidas = $pedido->updateCantidadProductosDetallesPedidos($cantidadRestante, $id_orden, $id_producto, $tipo);

                        if($resultUpdatedBebidas && $cantidadRestante == 0){
                            $pedido->deleteDetallePedidoByIdCantidad($id_orden);
                        }

                        $idsCreados[] = $insertedId;
                    }
                }

                
            }
        }

        if (!empty($platillos)) {
            foreach ($platillos as $id_producto => $datos_producto) {

                $cantidad = intval($datos_producto['cantidadPagar']);
                $pedido = new PedidosModel();
                $tipo = 'id_platillo';
                $cantidadExistente = intval($pedido->getCantidadByIdPedidoAndIdProducto($id_orden, $id_producto, $tipo));

                if($cantidad !== 0 && $cantidad <= $cantidadExistente){
                    $pagosModel = new PagosPersona();
                    $pagosModel->setIdFactura($id_orden);
                    $pagosModel->setNombre($nombre);
                    $pagosModel->setTelefono($telefono);

                    $precio = $datos_producto['precio'];
                    $productoNombre = $datos_producto['nombre_producto'];
                    $id__producto = $datos_producto['id_producto'];

                    $pagosModel->setTipoProducto(2);
                    $pagosModel->setPrecio($precio);
                    $pagosModel->setCantidad($cantidad); 
                    $pagosModel->setNombreProducto($productoNombre); 
                    $pagosModel->setIdProducto($id__producto); 
                    $insertedId = $pagosModel->nuevoPagoFactura();

                    if($insertedId > 0 && $cantidadExistente > 0){
                        $cantidadRestante = $cantidadExistente - $cantidad;
                        $resultUpdatedPlatillos = $pedido->updateCantidadProductosDetallesPedidos($cantidadRestante, $id_orden, $id_producto, $tipo);

                        if($resultUpdatedPlatillos && $cantidadRestante == 0){
                            $pedido->deleteDetallePedidoByIdCantidad($id_orden);
                        }

                        $idsCreados[] = $insertedId;
                    }
                }

                
            }
        }

        if ($resultUpdatedBebidas || $resultUpdatedPlatillos) {
            $nuevoPedido = new PedidosModel();
            $monto = $nuevoPedido->getMontoProductos($id_orden);
            
            // Calcula el nuevo monto con un incremento del 10%
            $nuevoMonto = ($monto * 0.10) + $monto;
            
            // Establece el nuevo monto en el pedido
            $nuevoPedido->setIdPedido($id_orden);
            $nuevoPedido->setTotalRestante($nuevoMonto);
            
            // Actualiza el monto total del pedido
            $updateMontos = $nuevoPedido->updateTotalRestateById();

            $minimo = 0;
            $maximo = 0;

            if (!empty($idsCreados)) {
                $minimo = min($idsCreados);
                $maximo = max($idsCreados);
            }
            
            if ($updateMontos) {
                header("Location: ../views/facturarSeparado.php?idPedido=$id_orden&updatedFactura=1&min=$minimo&max=$maximo");
                exit();
            } else {
                header("Location: ../views/facturarSeparado.php?idPedido=$id_orden&updatedFactura=0");
                exit();
            }
        }else{
            header("Location: ../views/facturarSeparado.php?idPedido=$id_orden&updatedFactura=0");
            exit();
        }                

            // if(!empty($platillos)){

            //     foreach ($platillos as $id_platillo  => $cantidad) {
            //         if (!empty($cantidad)) {
            //             $insertedPlatillos = $pedidosModel->newDetallePedidoPlatillo($insertedId, $id_platillo, $cantidad);
            //         }
            //     }
            // }

            // if($insertedPlatillos || $insertedBebidas){

            //     $montoTotal = $pedidosModel->getMontoProductos($insertedId);
            //     $pedidosUpdate = new PedidosModel();
            //     $pedidosUpdate->setIdPedido($insertedId);
            //     $pedidosUpdate->setTotalPedido($montoTotal);
            //     $updateMonto = $pedidosUpdate->updateTotalPedidoById();

            //     if($updateMonto){
            //         header("Location: ../views/ordenes.php?orderAdd=1");
            //         exit();
            //     }else{
            //         header("Location: ../views/ordenes.php?orderAdd=0");
            //         exit();
            //     }

                
                
            // }else{
            //     header("Location: ../views/ordenes.php?orderAdd=0");
            //     exit();
            // }
        
    }else{
        header("Location: ../views/nuevaOrdenExpress.php?emptyProducts=0");
        exit();
    }  
}







