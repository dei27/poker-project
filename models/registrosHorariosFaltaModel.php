<?php
require_once('dbModel.php');

class RegistrosFaltasHorarios extends BaseModel {

    private $id_registro_falta;
    private $id_tipo_falta;
    private $id_usuario;
    private $fecha_inicio;
    private $fecha_fin;
    private $notas;

    public function setIdRegistroFalta($id_registro_falta) {
        $this->id_registro_falta = $id_registro_falta;
    }

    public function setIdTipoFalta($id_tipo_falta) {
        $this->id_tipo_falta = $id_tipo_falta;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function setFechaInicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function setFechaFin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }

    public function setNotas($notas) {
        $this->notas = $notas;
    }

    public function __construct($id_registro_falta = null, $id_tipo_falta = null, $id_usuario = null, $fecha_inicio = null, $fecha_fin = null, $notas = null) {
        parent::__construct();
        $this->id_registro_falta = $id_registro_falta;
        $this->id_tipo_falta = $id_tipo_falta;
        $this->id_usuario = $id_usuario;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->notas = $notas;
    }
    

    public function getAllRegistrosFaltasHorarios() {
        try {
            $query = "SELECT rhf.*, tfh.nombre_tipo_falta as nombre_tipo FROM registros_horarios_faltas rhf INNER JOIN tipos_faltas_horarios tfh ON rhf.id_tipo_falta = tfh.id_tipo_falta;";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getAllRegistrosFaltasHorariosByIdUsuario() {
        try {
            $query = "SELECT rhf.*, tfh.nombre_tipo_falta as nombre_tipo FROM registros_horarios_faltas rhf INNER JOIN tipos_faltas_horarios tfh ON rhf.id_tipo_falta = tfh.id_tipo_falta WHERE rhf.id_usuario = :id_usuario;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }


    public function newRegistroFalta() {
        try {
            $query = "INSERT INTO registros_horarios_faltas (id_registro_falta, id_tipo_falta, id_usuario, fecha_inicio, fecha_fin, notas ) VALUES (:id_registro_falta,  :id_tipo_falta, :id_usuario, :fecha_inicio, :fecha_fin, :notas)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_registro_falta', $this->id_registro_falta, PDO::PARAM_INT);
            $stmt->bindParam(':id_tipo_falta', $this->id_tipo_falta, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_inicio', $this->fecha_inicio, PDO::PARAM_STR);
            $stmt->bindParam(':fecha_fin', $this->fecha_fin, PDO::PARAM_STR);
            $stmt->bindParam(':notas', $this->notas, PDO::PARAM_STR);
            $success = $stmt->execute();
            return $success;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
}