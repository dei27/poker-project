<?php
require_once('dbModel.php');

class Torneo extends BaseModel {

    private $id;
    private $nombre;
    private $lugar;
    private $fecha;
    private $hora;
    private $entrada;
    private $recompra;
    private $add_on;
    private $ganador;

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getLugar() {
        return $this->lugar;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getHora() {
        return $this->hora;
    }

    public function getEntrada() {
        return $this->entrada;
    }

    public function getRecompra() {
        return $this->recompra;
    }

    public function getAddOn() {
        return $this->add_on;
    }

    public function getGanador() {
        return $this->ganador;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setLugar($lugar) {
        $this->lugar = $lugar;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setHora($hora) {
        $this->hora = $hora;
    }

    public function setEntrada($entrada) {
        $this->entrada = $entrada;
    }

    public function setRecompra($recompra) {
        $this->recompra = $recompra;
    }

    public function setAddOn($addOn) {
        $this->add_on = $addOn;
    }

    public function setGanador($ganador) {
        $this->ganador = $ganador;
    }

    public function __construct($nombre = null, $lugar = null, $fecha = null, $hora = null, $entrada = null, $recompra = null, $add_on = null, $ganador = null) {
        parent::__construct();
        $this->nombre = $nombre;
        $this->lugar = $lugar;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->entrada = $entrada;
        $this->recompra = $recompra;
        $this->add_on = $add_on;
        $this->ganador = $ganador;
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM torneo";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function deleteById($id) {
        try {
            $query = "DELETE FROM torneo WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updateTorneo() {
        try {
            $query = "UPDATE torneo SET nombre = :nombre, fecha = :fecha, hora = :hora, entrada = :entrada, recompra = :recompra, add_on = :addon WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':fecha', $this->fecha, PDO::PARAM_STR);
            $stmt->bindParam(':hora', $this->hora, PDO::PARAM_STR);
            $stmt->bindParam(':entrada', $this->entrada, PDO::PARAM_INT);
            $stmt->bindParam(':recompra', $this->recompra, PDO::PARAM_INT);
            $stmt->bindParam(':addon', $this->add_on, PDO::PARAM_INT);
            
            $stmt->execute();
            
            return true;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
}