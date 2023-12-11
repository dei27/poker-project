<?php
require_once('dbModel.php');

class Torneo extends BaseModel {

    protected $id;
    protected $nombre;
    protected $lugar;
    protected $fecha;
    protected $hora;
    protected $entrada;
    protected $recompra;
    protected $add_on;
    protected $ganador;

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
    
}