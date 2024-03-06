<?php
require_once('dbModel.php');

class TiposFaltasHorarios extends BaseModel {

    private $id_tipo_falta;
    private $nombre_tipo_falta;

    public function setId($id_tipo_falta) {
        $this->id_tipo_falta = $id_tipo_falta;
    }

    public function setNombre($nombre_tipo_falta) {
        $this->nombre_tipo_falta = $nombre_tipo_falta;
    }


    public function __construct($id_tipo_falta = null, $nombre_tipo_falta = null) {
        parent::__construct();
        $this->id_tipo_falta = $id_tipo_falta;
        $this->nombre_tipo_falta = $nombre_tipo_falta;
    }

    public function getAllTiposFaltasHorarios() {
        try {
            $query = "SELECT * FROM tipos_faltas_horarios ORDER BY nombre_tipo_falta ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
}