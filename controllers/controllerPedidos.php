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

function pagarFactura($id_orden, $nombre, $telefono, $tipoProducto, $precio, $cantidad, $fecha_pago, $productoNombre, $id__producto, $metodo_pago, $mesaPedido) {
    $pagosModel = new PagosPersona();
    $pagosModel->setIdFactura($id_orden);
    $pagosModel->setNombre($nombre);
    $pagosModel->setTelefono($telefono);
    $pagosModel->setTipoProducto($tipoProducto);
    $pagosModel->setPrecio($precio);
    $pagosModel->setCantidad($cantidad); 
    $pagosModel->setFechaPago($fecha_pago); 
    $pagosModel->setNombreProducto($productoNombre); 
    $pagosModel->setIdProducto($id__producto); 
    $pagosModel->setMetodoPago($metodo_pago); 
    $pagosModel->setMesa($mesaPedido); 
    $insertedId = $pagosModel->nuevoPagoFactura();
    return $insertedId;
}

function getPedidoById($id_pedido){
    $pedidosModel = new PedidosModel();
    $pedido = $pedidosModel->getPedidosById($id_pedido);
    return $pedido; 
}

function getEstadoPedido($id_pedido){
    $pedidosModel = new PedidosModel();
    $pedido = $pedidosModel->getEstadoPedidoById($id_pedido);
    return $pedido; 
}

function facturarPedidoById($id_pedido){
    $pedidosModel = new PedidosModel();
    $pedido = $pedidosModel->facturarById($id_pedido);
    return $pedido; 
}

function redirgir(){

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
    $mesa = isset($_POST['mesaOrden']) ? htmlspecialchars($_POST['mesaOrden'], ENT_QUOTES, 'UTF-8') : null; 
    $telefono = isset($_POST['telefonoClienteOrden']) ? htmlspecialchars($_POST['telefonoClienteOrden'], ENT_QUOTES, 'UTF-8') : "Sin teléfono";   
    $servicio = 10;
    $inputs = $_POST['cantidad'];
    $bebidas = [];
    $platillos = [];
    
    if(!empty($inputs)){
        $pedidosModel = new PedidosModel();
        $pedidosModel->setNombreCliente($nombre);
        $pedidosModel->setMesa($mesa);
        $pedidosModel->setTelefonoCliente($telefono);
        $pedidosModel->setDireccionCliente(null);
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
                        $insertedBebidas = $pedidosModel->newDetallePedidoBebida($insertedId, $id_bebida, $cantidad, 0);
                    }
                }
            }

            if(!empty($platillos)){

                foreach ($platillos as $id_platillo  => $cantidad) {
                    if (!empty($cantidad)) {
                        $insertedPlatillos = $pedidosModel->newDetallePedidoPlatillo($insertedId, $id_platillo, $cantidad, 0);
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
    $direccion = isset($_POST['direccionClienteOrden']) ? htmlspecialchars($_POST['direccionClienteOrden'], ENT_QUOTES, 'UTF-8') : null; 
    $inputs = $_POST['cantidad'];
    $bebidas = [];
    $platillos = [];

    if($direccion === null){
        header("Location: ../views/ordenes.php?orderAdd=0");
        exit(); 
    }
    
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
                        $insertedBebidas = $pedidosModel->newDetallePedidoBebida($insertedId, $id_bebida, $cantidad, 0);
                    }
                }
            }

            if(!empty($platillos)){

                foreach ($platillos as $id_platillo  => $cantidad) {
                    if (!empty($cantidad)) {
                        $insertedPlatillos = $pedidosModel->newDetallePedidoPlatillo($insertedId, $id_platillo, $cantidad, 0);
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
    $direccion = isset($_POST['direccionClienteUpdate']) ? (trim($_POST['direccionClienteUpdate']) !== '' ? htmlspecialchars($_POST['direccionClienteUpdate'], ENT_QUOTES, 'UTF-8') : "Sin dirección") : null;
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
                        $check = "B" . $id_bebida;
                        $entregado = isset($_POST['productoEntregado'][$check]) ? $_POST['productoEntregado'][$check] : 0;
                        $insertedBebidas = $pedidosModel->newDetallePedidoBebida($id_orden, $id_bebida, $cantidad, $entregado);

                        if($entregado == 1  && $insertedBebidas){
                            $pedidosModel->updateInventarioBebidasByIdReceta($id_bebida, $cantidad);
                        }
                    }
                }
            }

            if(!empty($platillos)){

                foreach ($platillos as $id_platillo  => $cantidad) {
                    if (!empty($cantidad)) {
                        $check = "R" . $id_platillo;
                        $entregado = isset($_POST['productoEntregado'][$check]) ? $_POST['productoEntregado'][$check] : 0;
                        $insertedPlatillos = $pedidosModel->newDetallePedidoPlatillo($id_orden, $id_platillo, $cantidad, $entregado);

                        if($entregado == 1 && $insertedPlatillos){

                            $ingredientesRecetasPrincipal = $pedidosModel->getAllIngredientesRecetaByIdRecta($id_platillo);

                            foreach ($ingredientesRecetasPrincipal as $ingrediente) {

                                $id_ingrediente_receta = $ingrediente['id_ingrediente'];
                                $id_ingrediente_cantidad = $ingrediente['cantidad'];

                                $cantidad_total_consumida = $id_ingrediente_cantidad * $cantidad;

                                $updateInventarioIngredientes = $pedidosModel->updateInventarioProductosByIdReceta($id_ingrediente_receta, $cantidad_total_consumida);
                            }

                            $dataRecetas = $pedidosModel->getAllRecetasCombinasById($id_platillo);

                            if(!empty($dataRecetas)){

                                foreach ($dataRecetas as $receta) {
                                    $id_receta_compuesta = $receta['id_receta_compuesta'];
                                    $cantidad_receta_compuesta = $receta['cantidad_receta_compuesta'];
    
                                    $ingredientesRecetas = $pedidosModel->getAllIngredientesRecetaByIdRecta($id_receta_compuesta);

                                    foreach ($ingredientesRecetas as $ingrediente) {

                                        $id_ingrediente_receta = $ingrediente['id_ingrediente'];
                                        $id_ingrediente_cantidad = $ingrediente['cantidad'];
        
                                        $cantidad_total_consumida = $id_ingrediente_cantidad * $cantidad * $cantidad_receta_compuesta;
        
                                        $updateInventarioIngredientes = $pedidosModel->updateInventarioProductosByIdReceta($id_ingrediente_receta, $cantidad_total_consumida);
                                    }
                                }

                            }
                        }
                    }
                }
            }

            if($insertedPlatillos || $insertedBebidas){

                $montoTotal = $pedidosModel->getMontoProductos($id_orden);

                if($mesaOrdenUpdate !== null){
                    $montoTotalConServicio = $montoTotal + ($montoTotal * $servicio / 100);
                }else{
                    $montoTotalConServicio = $montoTotal;
                }


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
        }http://localhost/monchister/views/login.php
        
    }else{
        header("Location: ../views/actualizarOrden.php?idPedido=$id_orden&emptyProducts=0");
        exit();
    }
}


if (isset($_POST['action']) && $_POST['action'] === 'procesarPago') {
    $id_orden = filter_input(INPUT_POST, 'idOrden', FILTER_SANITIZE_NUMBER_INT);
    $nombre = isset($_POST['nombreClienteProcesarSeparado']) ? htmlspecialchars($_POST['nombreClienteProcesarSeparado'], ENT_QUOTES, 'UTF-8') : "Sin nombre"; 
    $telefono = isset($_POST['telefonoProcesarSeparado']) ? htmlspecialchars($_POST['telefonoProcesarSeparado'], ENT_QUOTES, 'UTF-8') : "Sin teléfono"; 
    $mesa = isset($_POST['mesaOrdenProcesarSeparado']) ? filter_input(INPUT_POST, 'mesaOrdenProcesarSeparado', FILTER_SANITIZE_NUMBER_INT) : null;
    $metodo_pago = isset($_POST['metodoPago']) ? htmlspecialchars($_POST['metodoPago'], ENT_QUOTES, 'UTF-8') : 1; 
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
                    $pagosModel->setMetodoPago($metodo_pago); 
                    $pagosModel->setMesa($mesa); 
                    $insertedId = $pagosModel->nuevoPagoFactura();

                    if($insertedId > 0 && $cantidadExistente > 0){
                        $cantidadRestante = $cantidadExistente - $cantidad;
                        $resultUpdatedBebidas = $pedido->updateCantidadProductosDetallesPedidos($cantidadRestante, $id_orden, $id_producto, $tipo);

                        if($resultUpdatedBebidas && $cantidadRestante == 0){
                            $sinEliminarBebidas = $pedido->deleteDetallePedidoByIdCantidad($id_orden);
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
                    $pagosModel->setMetodoPago($metodo_pago); 
                    $pagosModel->setMesa($mesa); 
                    $insertedId = $pagosModel->nuevoPagoFactura();

                    if($insertedId > 0 && $cantidadExistente > 0){
                        $cantidadRestante = $cantidadExistente - $cantidad;
                        $resultUpdatedPlatillos = $pedido->updateCantidadProductosDetallesPedidos($cantidadRestante, $id_orden, $id_producto, $tipo);

                        if($resultUpdatedPlatillos && $cantidadRestante == 0){
                            $sinEliminarPlatillos = $pedido->deleteDetallePedidoByIdCantidad($id_orden);
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

            $dataDetallesPedido = $nuevoPedido->getAllDetallesPedidoByIdPedido();

            if(empty($dataDetallesPedido)){
                $nuevoPedido->updateEstadoPedidoById();
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
        
    }else{
        header("Location: ../views/facturarSeparado.php?idPedido=$id_orden&emptyProducts=0");
        exit();
    }  
}

if (isset($_POST['action']) && $_POST['action'] == "facturarPago") {
    $id_factura = filter_input(INPUT_POST, 'idFactura', FILTER_SANITIZE_NUMBER_INT) ?: false;
    $cantidadPersonas = filter_input(INPUT_POST, 'cantidadPersonas', FILTER_SANITIZE_NUMBER_INT) ?: false;
    $mesaPedido = filter_input(INPUT_POST, 'mesaPedido', FILTER_SANITIZE_NUMBER_INT) ?: null;
    $metodo_pago = isset($_POST['metodoPago']) ? htmlspecialchars($_POST['metodoPago'], ENT_QUOTES, 'UTF-8') : 1; 

    if($id_factura && $cantidadPersonas){

    $estado_pedido = getEstadoPedido($id_factura);

    $pedido = new PedidosModel();
    $productos = new PedidosModel();

    $datosPedido  = $pedido->getPedidosById($id_factura);
    $dataProductos = $productos->getAllDetallesPedidosAndPreciosPedidos($id_factura);

    $bebidas = array();
    $platillos = array();
    $preciosPlatillos = 0;
    $preciosBebidas = 0;
    $insertados = array();

    foreach ($dataProductos as $producto) {

        $product_id = substr($producto['product_id'], 1);

        if (substr($producto['product_id'], 0, 1) === 'R') {

            $platillos[] = array(
                'producto' => $producto['producto'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad'],
                'product_id' => $product_id
            );
        } else {
            $bebidas[] = array(
                'producto' => $producto['producto'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad'],
                'product_id' => $product_id
            );
        }
    }

    date_default_timezone_set('America/Costa_Rica');
    $fecha_actual = date(time());

    echo $fecha_actual ;

    if (!empty($platillos)) {
        foreach ($platillos as $platillo) {
            $preciosPlatillos += $platillo['precio'] * $platillo['cantidad'];

            if($estado_pedido != "Cancelado"){
                $insertedIdPago = pagarFactura($id_factura, $datosPedido['nombre_cliente'], $datosPedido['telefono_cliente'], 2, $platillo['precio'], $platillo['cantidad'], $fecha_actual, $platillo['producto'], $platillo['product_id'], $metodo_pago, $mesaPedido);

                $cantidadBebida = intval($platillo['cantidad']);
                $pedidoBebida = new PedidosModel();
                $tipoBebida = 'platillo';
                $cantidadExistenteBebida = intval($pedidoBebida->getCantidadByIdPedidoAndIdProducto($id_factura, $platillo['product_id'], $tipoBebida));

                if($cantidadBebida !== 0 && $cantidadBebida <= $cantidadExistenteBebida){

                    if($insertedIdPago > 0 && $cantidadExistenteBebida > 0){
                        $cantidadRestante = $cantidadExistenteBebida - $cantidadBebida;
                        $resultUpdatedBebidas = $pedidoBebida->updateCantidadProductosDetallesPedidos($cantidadRestante, $id_factura, $platillo['product_id'], $tipoBebida);

                        if($resultUpdatedBebidas && $cantidadRestante == 0){
                            $insertedResult = $pedidoBebida->deleteDetallePedidoByIdCantidad($id_factura);
                        }
                    }
                    $insertados[] = $insertedIdPago;
                }

                
            }
        }
    }
    
    if (!empty($bebidas)) {

        foreach ($bebidas as $bebida) {

            if($estado_pedido != "Cancelado"){
                $insertedIdPago = pagarFactura($id_factura, $datosPedido['nombre_cliente'], $datosPedido['telefono_cliente'], 1, $bebida['precio'], $bebida['cantidad'], $fecha_actual, $bebida['producto'], $bebida['product_id'], $metodo_pago, $mesaPedido);

                $cantidadBebida = intval($bebida['cantidad']);
                $pedidoBebida = new PedidosModel();
                $tipoBebida = 'bebida';
                $cantidadExistenteBebida = intval($pedidoBebida->getCantidadByIdPedidoAndIdProducto($id_factura, $bebida['product_id'], $tipoBebida));

                if($cantidadBebida !== 0 && $cantidadBebida <= $cantidadExistenteBebida){

                    if($insertedIdPago > 0 && $cantidadExistenteBebida > 0){
                        $cantidadRestante = $cantidadExistenteBebida - $cantidadBebida;
                        $resultUpdatedBebidas = $pedidoBebida->updateCantidadProductosDetallesPedidos($cantidadRestante, $id_factura, $bebida['product_id'], $tipoBebida);

                        if($resultUpdatedBebidas && $cantidadRestante == 0){
                            $insertedResult = $pedidoBebida->deleteDetallePedidoByIdCantidad($id_factura);
                        }
                    }

                    $insertados[] = $insertedIdPago;
                }
                
            }
        }
    }

        if($estado_pedido != "Cancelado"){
            $facturarPedido = facturarPedidoById($id_factura);
        }

        if(!empty($insertados)){

            $minimo = min($insertados);
            $maximo = max($insertados);
            $url = "";

            if($mesaPedido !== null){
                $url = "facturada=1&i=$id_factura&c=$cantidadPersonas&min=$minimo&max=$maximo";
            }else{
                $url = "facturada=1&min=$minimo&max=$maximo";
            }

            header("Location: ../views/ordenes.php?$url ");
            exit();
        }else{
            header("Location: ../views/ordenes.php?facturada=0");
            exit();
        } 
    }else{
        header("Location: ../views/ordenes.php?facturada=0");
        exit();
    }
}


if (isset($_POST['action']) && $_POST['action'] == "calcularClientes" && $_POST['idOrden'] && $_POST['cantidadClientes']) {
    $id_factura = filter_input(INPUT_POST, 'idOrden', FILTER_SANITIZE_NUMBER_INT) ?: false;
    $cantidadPersonas = filter_input(INPUT_POST, 'cantidadClientes', FILTER_SANITIZE_NUMBER_INT) ?: false;

    if($id_factura && $cantidadPersonas){
        header("Location: ../views/facturarClientes.php?idFactura=$id_factura&c=$cantidadPersonas");
        exit();
    }
}

if (isset($_POST['action']) && $_POST['action'] == "facturarSeparado") {

    $dataToSend = []; // Array para almacenar los datos a enviar}

    foreach ($_POST as $key => $value) {
        // Verificar si el nombre de la clave comienza con 'form_'
        if (strpos($key, 'form_') === 0 && is_array($value)) {
            // Obtener el número de índice del conjunto de datos
            $index = substr($key, 5);

            $nombreCliente = $value['nombreClienteProcesarSeparado'] ?? '';
            $telefonoCliente = $value['telefonoProcesarSeparado'] ?? '';
            $mesa = $value['mesaOrdenProcesarSeparado'] ?? '';
            $servicio = $value['servicioOrdenProcesarSeparado'] ?? '';
            $metodoPago = $value['metodoPago'] ?? '';
            
            // Agregar los datos al array de datos a enviar
            $dataToSend[] = [
                'nombreCliente' => $nombreCliente,
                'telefonoCliente' => $telefonoCliente,
                'mesa' => $mesa,
                'servicio' => $servicio,
                'metodoPago' => $metodoPago
            ];

            $id_factura = filter_input(INPUT_POST, 'idOrden', FILTER_SANITIZE_NUMBER_INT) ?: false;
            $cantidadPersonas = filter_input(INPUT_POST, 'c', FILTER_SANITIZE_NUMBER_INT) ?: false;
        }
    }
}
