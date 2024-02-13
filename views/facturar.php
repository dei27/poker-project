<?php
include('../controllers/controllerPedidos.php');
include('../controllers/controllerDetallesPedidos.php');

// Verificar si se ha proporcionado el ID de la factura a través de GET
if(isset($_GET["idFactura"])) {
    // Obtener el ID de la factura
    $id_factura = $_GET["idFactura"];

    facturarPedidoById($id_factura);

    $pedido = new PedidosModel();
    $productos = new DetallesPedidos();

    $datosPedido  = $pedido->getPedidosById($id_factura);
    $dataProductos = $productos->getAllDetallesPedidosAndPrecios($id_factura);

    $fecha_original = "2024-02-08 14:43:36";
    $fecha_formateada = date("d-m-Y H:i:s", strtotime($datosPedido['fecha_pedido']));

    $nombreCajero = isset($_SESSION['user']['nickname']) ? $_SESSION['user']['nickname'] : 'Desconocido';

    $contenido = "Nombre del comercio: Monchister SRL". PHP_EOL;
    $contenido .= "Número de teléfono: +1 (555) 123-4567". PHP_EOL;
    $contenido .= "Dirección: Calle Ficticia 123, Ciudad Ficticia". PHP_EOL;
    $contenido .= "Cédula jurídica: 1234567890". PHP_EOL;
    $contenido .= "Correo electrónico: info@monchister.com". PHP_EOL;
    $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
    $contenido .= " " . PHP_EOL;
    $contenido .= "Factura #" . $datosPedido['id_pedido'] . PHP_EOL;
    $contenido .= "Fecha: " . $fecha_formateada . PHP_EOL;
    $contenido .= "Nombre del Cliente: " . $datosPedido['nombre_cliente'] . PHP_EOL;
    $contenido .= "Teléfono del Cliente: " . $datosPedido['telefono_cliente'] . PHP_EOL;
    $contenido .= "Dirección del Cliente: " . $datosPedido['direccion_cliente'] . PHP_EOL;
    $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
    $contenido .= " " . PHP_EOL;
    $contenido .= "Productos de la orden:" . PHP_EOL;
    $bebidas = array();
    $platillos = array();
    $preciosPlatillos = 0;
    $preciosBebidas = 0;

    foreach ($dataProductos as $producto) {
        if (substr($producto['product_id'], 0, 1) === 'R') {

            $platillos[] = array(
                'producto' => $producto['producto'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad']
            );
        } else {
            $bebidas[] = array(
                'producto' => $producto['producto'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad']
            );
        }
    }

    if (!empty($platillos)) {
        // $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        $contenido .= "Platillos:" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        foreach ($platillos as $platillo) {
            $contenido .= $platillo['producto'] . ' - Precio: ₡' . $platillo['precio'] . ' - Cantidad: ' . $platillo['cantidad'] . PHP_EOL;
            $preciosPlatillos += $platillo['precio'] * $platillo['cantidad'];
        }
    }
    
    if (!empty($bebidas)) {
        // $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        $contenido .= "Bebidas:" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        foreach ($bebidas as $bebida) {
            $contenido .= $bebida['producto'] . ' - Precio: ₡' . $bebida['precio'] . ' - Cantidad: ' . $bebida['cantidad'] . PHP_EOL;
            $preciosBebidas += $bebida['precio'] * $bebida['cantidad'];
        }
    }

    $contenido .= " " . PHP_EOL;
    $contenido .= "Subtotal de la Orden: ₡" . $preciosPlatillos + $preciosBebidas . PHP_EOL;
    $contenido .= " " . PHP_EOL;
    $contenido .= "Servicio 10%: ₡" . ($preciosPlatillos + $preciosBebidas)*0.10 . PHP_EOL;
    $contenido .= " " . PHP_EOL;
    $contenido .= "Total de la Orden: ₡" . $datosPedido['total_pedido'] . PHP_EOL;
    $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
    $contenido .= " " . PHP_EOL;
    $contenido .= "Estado de la Orden: " . $datosPedido['estado_pedido'] . PHP_EOL;
    $contenido .= " " . PHP_EOL;
    $contenido .= "Cancelado por: " . $nombreCajero . PHP_EOL;


    // Crear un archivo temporal
    $archivoTemporal = tempnam(sys_get_temp_dir(), 'factura_');

    // Escribir el contenido en el archivo temporal
    file_put_contents($archivoTemporal, $contenido);

    // Configurar los encabezados para la descarga del archivo
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="Factura_' . $id_factura . '.txt"');
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
    echo "No se proporcionó el ID de la factura a través de GET.";
}
?>
