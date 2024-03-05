<?php
require_once('dbModel.php');

class CerrarCajaModel extends BaseModel {

    private $id;
    private $fecha_cierre;
    private $efectivo;
    private $tarjeta;
    private $sinpe;
    private $total_dia;

    public function __construct($fecha_cierre = null, $efectivo = null, $tarjeta = null, $sinpe = null, $total_dia = null) {
        parent::__construct();
        $this->fecha_cierre = $fecha_cierre;
        $this->efectivo = $efectivo;
        $this->tarjeta = $tarjeta;
        $this->sinpe = $sinpe;
        $this->total_dia = $total_dia;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setFechaCierre($fecha_cierre) {
        $this->fecha_cierre = $fecha_cierre;
    }
    
    public function setEfectivo($efectivo) {
        $this->efectivo = $efectivo;
    }
    
    public function setTarjeta($tarjeta) {
        $this->tarjeta = $tarjeta;
    }
    
    public function setSinpe($sinpe) {
        $this->sinpe = $sinpe;
    }
    
    public function setTotalDia($total_dia) {
        $this->total_dia = $total_dia;
    }

    public function getAllCierresCajas() {
        try {
            $query = "SELECT * FROM cierres_cajas";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function nuevoCierreCaja() {
        try {
            $existingRecordQuery = "SELECT COUNT(*) FROM cierres_cajas WHERE DATE(fecha_cierre) = DATE(:fecha_cierre)";
            $existingRecordStmt = $this->conn->prepare($existingRecordQuery);
            $existingRecordStmt->bindParam(':fecha_cierre', $this->fecha_cierre, PDO::PARAM_STR);
            $existingRecordStmt->execute();
            $existingRecordCount = $existingRecordStmt->fetchColumn();
    
            if ($existingRecordCount > 0) {
                $query = "UPDATE cierres_cajas SET efectivo = :efectivo, tarjeta = :tarjeta, sinpe = :sinpe, total_dia = :total_dia WHERE DATE(fecha_cierre) = DATE(:fecha_cierre)";
            } else {
                $query = "INSERT INTO cierres_cajas (fecha_cierre, efectivo, tarjeta, sinpe, total_dia) VALUES (:fecha_cierre, :efectivo, :tarjeta, :sinpe, :total_dia)";
            }
    
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':fecha_cierre', $this->fecha_cierre, PDO::PARAM_STR);
            $stmt->bindParam(':efectivo', $this->efectivo, PDO::PARAM_STR);
            $stmt->bindParam(':tarjeta', $this->tarjeta, PDO::PARAM_STR);
            $stmt->bindParam(':sinpe', $this->sinpe, PDO::PARAM_STR);
            $stmt->bindParam(':total_dia', $this->total_dia, PDO::PARAM_STR);
            
            $success = $stmt->execute();
            return $success;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }
    
    
}