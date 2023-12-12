<?php
require_once('dbModel.php');

class Jugador extends BaseModel {

    protected $id;
    protected $nickname;
    protected $ciudad;
    protected $telefono;
    protected $email;

    public function __construct($nickname = null, $ciudad = null, $telefono = null, $email = null) {
        parent::__construct();
        $this->nickname = $nickname;
        $this->ciudad = $ciudad;
        $this->telefono = $telefono;
        $this->email = $email;
    }

    public function getAllPlayers() {
        try {
            $query = "SELECT * FROM jugadores";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getAllPlayersNotInRecords($idTorneo) {
        try {
            $query = "SELECT * FROM jugadores WHERE id NOT IN (SELECT jugador_id FROM registros where torneo_id = :idTorneo)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':idTorneo', $idTorneo, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function deleteById($id) {
        try {
            $query = "DELETE FROM jugadores WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
}