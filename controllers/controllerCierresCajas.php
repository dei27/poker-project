<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include(__DIR__ . '/../models/CerrarCajaModel.php');

function getAllCierresCajas() {
    $cierreCaja = new CerrarCajaModel();
    $cierresCajas = $cierreCaja->getAllCierresCajas();
    return json_encode($cierresCajas);
}


function nuevoCierreCaja($efectivo, $tarjeta, $sinpe, $total_dia) {
    $cierreCaja = new CerrarCajaModel($efectivo, $tarjeta, $sinpe, $total_dia);
    $cierresCajas = $cierreCaja->nuevoCierreCaja();
    return $cierresCajas;
}

if (isset($_POST['action']) && $_POST['action'] === 'nuevaCajaCierre') {
    

    $montoEfectivo = isset($_POST['efectivoCaja']) ? $_POST['efectivoCaja'] : 0.0;

    $montoTarjeta = isset($_POST['tarjetaCaja']) ? $_POST['tarjetaCaja'] : 0.0;

    $montoSinpe = isset($_POST['sinpeCaja']) ? $_POST['sinpeCaja'] : 0.0;

    $montoTotal = isset($_POST['totalCajaDia']) ? $_POST['totalCajaDia'] : 0.0;

    $cerrarCaja = new CerrarCajaModel();

    date_default_timezone_set('America/Costa_Rica');
    $hora_actual = date("Y-m-d H:i:s");
    $cerrarCaja->setFechaCierre($hora_actual);
    $cerrarCaja->setEfectivo($montoEfectivo);
    $cerrarCaja->setTarjeta($montoTarjeta);
    $cerrarCaja->setSinpe($montoSinpe);
    $cerrarCaja->setTotalDia($montoTotal);

    $result = $cerrarCaja->nuevoCierreCaja();

    if($result){
        header("Location: ../views/CerrarCaja.php?addCaja=1");
        exit();
    }else{
        header("Location: ../views/CerrarCaja.php?addCaja=0");
        exit();
    }


}












