<?php
require_once('dbModel.php');

class Registros extends BaseModel {

    private $id_registro;
    private $id_usuario;
    private $tipo;
    private $hora;

    public function setIdRegistro($id_registro)
    {
        $this->id_registro = $id_registro;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function setHora($hora)
    {
        $this->hora = $hora;
    }
    

    public function __construct($id_registro = null, $id_usuario = null , $tipo = null, $hora = null) {
        parent::__construct();
        $this->id_registro = $id_registro;
        $this->id_usuario = $id_usuario;
        $this->tipo = $tipo;
        $this->hora = $hora;
    }

    public function addRecordByIdUser() {
        try {
            $query = "INSERT INTO registros_horarios (id_usuario, tipo, hora) VALUES (:id_usuario, :tipo, :hora)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':tipo', $this->tipo, PDO::PARAM_INT);
            $stmt->bindParam(':hora', $this->hora, PDO::PARAM_STR);
            $stmt->execute();
            return true; 

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllTimesByUserId() {
        try {
            $query = "SELECT DISTINCT t.id_tipo_registro, t.nombre_tipo FROM tipos_registro_horarios t WHERE t.id_tipo_registro NOT IN (SELECT DISTINCT rh.tipo FROM registros_horarios rh WHERE rh.id_usuario = :id_usuario AND DATE_FORMAT(rh.hora, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d'))";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
}