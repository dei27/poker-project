<?php
include('../controllers/controllerPedidos.php');
include('../controllers/controllerDetallesPedidos.php');

if(isset($_GET["id"])) {
    $id_factura = $_GET["id"];

    $pedido = new PedidosModel();
    $productos = new DetallesPedidos();

    $datosPedido  = $pedido->getPedidosById($id_factura);
    $dataProductos = $productos->getAllDetallesPedidosAndPrecios($id_factura);

    $fecha_formateada = date("g:i:s A d-m-Y", strtotime($datosPedido['fecha_pedido']));

    $numeroMesa = $datosPedido['mesa'];

    $contenido = "Factura #" . $datosPedido['id_pedido'] . PHP_EOL;
    $contenido .= "Fecha: " . $fecha_formateada . PHP_EOL;

    if($datosPedido['mesa'] !== null){
        $contenido .= "Mesa: " . $datosPedido['mesa'] . PHP_EOL;
    }else{
        $contenido .= "Orden Express" . PHP_EOL;
    }

    $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
    $contenido .= " " . PHP_EOL;
    $contenido .= "Productos de la orden:" . PHP_EOL;
    $bebidas = array();
    $entradas = array();
    $platillos = array();
    $postres = array();
    $extras = array();

    foreach ($dataProductos as $producto) {

        switch ($producto['tipo']) {
            case '1':
                $entradas[] = array(
                'producto' => $producto['producto'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad']
                );
                break;
            case '2':
                $platillos[] = array(
                'producto' => $producto['producto'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad']
                );
                break;
            case '3':
                $postres[] = array(
                'producto' => $producto['producto'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad']
                );
                break;
            case '4':
                $extras[] = array(
                'producto' => $producto['producto'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad']
                );
                break;
            default:
                $bebidas[] = array(
                'producto' => $producto['producto'],
                'precio' => $producto['precio'],
                'cantidad' => $producto['cantidad']
                );
                break;
        }
    }

    if (!empty($bebidas)) {
        // $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        $contenido .= "Bebidas:" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        foreach ($bebidas as $bebida) {
            $contenido .= '->' . $bebida['producto'] . ' - Cantidad: ' . $bebida['cantidad'] . PHP_EOL;
        }
    }else{
        $contenido .= " " . PHP_EOL;
        $contenido .= "*** Sin bebidas ***" . PHP_EOL;
    }

    if (!empty($entradas)) {
        // $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        $contenido .= "Entradas:" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        foreach ($entradas as $bebida) {
            $contenido .= '->' . $bebida['producto'] . ' - Cantidad: ' . $bebida['cantidad'] . PHP_EOL;
        }
    }else{
        $contenido .= " " . PHP_EOL;
        $contenido .= "*** Sin entradas ***" . PHP_EOL;
    }

    if (!empty($platillos)) {
        // $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        $contenido .= "Platillos Fuertes:" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        foreach ($platillos as $platillo) {
            $contenido .= '->' . $platillo['producto'] . ' - Cantidad: ' . $platillo['cantidad'] . PHP_EOL;
        }
    }else{
        $contenido .= " " . PHP_EOL;
        $contenido .= "*** Sin platillos fuertes ***" . PHP_EOL;
    }
    
    if (!empty($postres)) {
        // $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        $contenido .= "Postres:" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        foreach ($postres as $bebida) {
            $contenido .= '->' . $bebida['producto'] . ' - Cantidad: ' . $bebida['cantidad'] . PHP_EOL;
        }
    }else{
        $contenido .= " " . PHP_EOL;
        $contenido .= "*** Sin postres ***" . PHP_EOL;
    }

    if (!empty($extras)) {
        // $contenido .= "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        $contenido .= "Extras:" . PHP_EOL;
        $contenido .= " " . PHP_EOL;
        foreach ($extras as $bebida) {
            $contenido .= '->' . $bebida['producto'] . ' - Cantidad: ' . $bebida['cantidad'] . PHP_EOL;
        }
    }else{
        $contenido .= " " . PHP_EOL;
        $contenido .= "*** Sin extras ***" . PHP_EOL;
    }

    $contenido .= " " . PHP_EOL;

    // Crear un archivo temporal
    $archivoTemporal = tempnam(sys_get_temp_dir(), 'factura_');

    // Escribir el contenido en el archivo temporal
    file_put_contents($archivoTemporal, $contenido);

    // Configurar los encabezados para la descarga del archivo
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="Comanda_Orden_' . $id_factura . '.txt"');
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
