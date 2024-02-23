<?php
include('../controllers/controllerPedidos.php');
include('../controllers/controllerDetallesPedidos.php');

if(isset($_GET["idFactura"]) && isset($_GET["min"]) && isset($_GET["max"])) {
    $id_factura = $_GET["idFactura"];
    $minimo = $_GET["min"];
    $maximo = $_GET["max"];
    $bebidas = array();
    $platillos = array();
    $preciosPlatillos = 0;
    $preciosBebidas = 0;
    $mesa = 0;
    $fecha = "";
    $cliente = "";
    $telefono = "";
    $metodo_pago = 0;


    $pedido = new PedidosModel();
    $productos = new DetallesPedidos();
    $datosPedido  = $pedido->getPagosPorPersona($minimo, $maximo);

    foreach ($datosPedido as $producto) {

        if ($producto['tipo_producto'] == 1) {

            $bebidas[] = array(
                'cliente' => $producto['nombre'],
                'telefono' => $producto['telefono'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad'],
                'fecha' => $producto['fecha_pago'],
                'producto' => $producto['nombre_producto'],
                'mesa' => $producto['mesa']
            );

            $mesa = $producto['mesa'];
            $fecha = date("g:i:s A d-m-Y", strtotime($producto['fecha_pago']));
            $cliente = $producto['nombre'];
            $telefono = $producto['telefono'];
            $metodo_pago = intval($producto['metodo_pago']);

        }elseif ($producto['tipo_producto'] == 2) {

            $platillos[] = array(
                'cliente' => $producto['nombre'],
                'telefono' => $producto['telefono'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad'],
                'fecha' => $producto['fecha_pago'],
                'producto' => $producto['nombre_producto'],
                'mesa' => $producto['mesa']
            );

            $mesa = $producto['mesa'];
            $fecha = date("g:i:s A d-m-Y", strtotime($producto['fecha_pago']));
            $cliente = $producto['nombre'];
            $telefono = $producto['telefono'];
            $metodo_pago = intval($producto['metodo_pago']);
        }
    }

    $nombreCajero = isset($_SESSION['user']['nickname']) ? $_SESSION['user']['nickname'] : 'Desconocido';

    $contenido = "Nombre del comercio: Monchister SRL". PHP_EOL;
    $contenido .= "Número de teléfono: +1 (555) 123-4567". PHP_EOL;
    $contenido .= "Dirección: Calle Ficticia 123, Ciudad Ficticia". PHP_EOL;
    $contenido .= "Cédula jurídica: 3-102-891367". PHP_EOL;
    $contenido .= "Correo electrónico: info@monchister.com". PHP_EOL;
    $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
    $contenido .= " " . PHP_EOL;
    $contenido .= "Factura #" . $id_factura . PHP_EOL;
    $contenido .= "Fecha: " . $fecha . PHP_EOL;
    $contenido .= "Nombre del Cliente: " . $cliente . PHP_EOL;
    $contenido .= "Teléfono del Cliente: " . $telefono . PHP_EOL;

    if($mesa !== null){
        $contenido .= "Mesa: " . $mesa. PHP_EOL;
    }

    $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
    $contenido .= " " . PHP_EOL;
    $contenido .= "Productos de la orden:" . PHP_EOL;

    if (!empty($platillos)) {
        // $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        $contenido .= "Platillos:" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        foreach ($platillos as $platillo) {
            $contenido .= '->' . $platillo['producto'] . ' - Precio: ₡' .  number_format($platillo['precio'], 2) . ' - Cantidad: ' . $platillo['cantidad'] . PHP_EOL;
            $preciosPlatillos += $platillo['precio'] * $platillo['cantidad'];
        }
    }
    
    if (!empty($bebidas)) {
        // $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        $contenido .= "Bebidas:" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        foreach ($bebidas as $bebida) {
            $contenido .= '->' . $bebida['producto'] . ' - Precio: ₡' .  number_format($bebida['precio'], 2) . ' - Cantidad: ' . $bebida['cantidad'] . PHP_EOL;
            $preciosBebidas += $bebida['precio'] * $bebida['cantidad'];
        }
    }

    $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
    $contenido .= " " . PHP_EOL;
    $subtotal = $preciosPlatillos + $preciosBebidas;
    $servicio10 = ($preciosPlatillos + $preciosBebidas)*0.10;
    $totalOrden = $subtotal + $servicio10;
    $contenido .= "Subtotal de la Orden: ₡" . number_format($subtotal, 2) . PHP_EOL;
    $contenido .= " " . PHP_EOL;

    if($mesa !== null){
        $contenido .= "Servicio 10%: ₡" . number_format($servicio10, 2) . PHP_EOL;
        $contenido .= " " . PHP_EOL;
    }
    $contenido .= "Total por pagar: ₡" . number_format($totalOrden, 2) . PHP_EOL;
    
    $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
    $contenido .= " " . PHP_EOL;

    switch ($metodo_pago)
    {
        case 1:
            $metodo_pago = "Efectivo";
            break;
        case 2:
            $metodo_pago = "Tarjeta";
            break;
        case 3:
            $metodo_pago = "Sinpe";
            break;
        default:
            $metodo_pago = "Sin método de pago";
            break;
    }

    $contenido .= "Método de pago: " . $metodo_pago . PHP_EOL;
    $contenido .= " " . PHP_EOL;
    $contenido .= "Cancelado por: " . $nombreCajero . PHP_EOL;


    // Crear un archivo temporal
    $archivoTemporal = tempnam(sys_get_temp_dir(), 'factura_');

    // Escribir el contenido en el archivo temporal
    file_put_contents($archivoTemporal, $contenido);

    // Configurar los encabezados para la descarga del archivo
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="Factura_' . $id_factura . '_Cliente_' . $cliente . '_Fecha_' . $fecha . '.txt"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($archivoTemporal));

    // Leer el contenido del archivo temporal y enviarlo al navegador
    readfile($archivoTemporal);

    // Eliminar el archivo temporal después de descargarlo
    unlink($archivoTemporal);

    exit; // Terminar la ejecución del script después de la descarga del archivo
} else {
    echo "No se proporcionaron los datos necesarios.";
}
?>
