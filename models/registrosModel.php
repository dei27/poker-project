<?php
require_once('dbModel.php');

class Registros extends BaseModel {

    private $id;
    private $torneoId;
    private $jugadorId;

    public function setTorneoId($torneoId) {
        $this->torneoId = $torneoId;
    }

    public function setJugadorId($jugadorId) {
        $this->jugadorId = $jugadorId;
    }

    public function getTorneoId() {
        return $this->torneoId;
    }

    public function getJugadorId() {
        return $this->jugadorId;
    }


    public function __construct($torneoId = null, $jugadorId = null) {
        parent::__construct();
        $this->torneoId = $torneoId;
        $this->jugadorId = $jugadorId;
    }

    public function getAllRecords($torneoId) {
        try {
            $query = "SELECT jugador_id, nickname FROM registros r INNER JOIN jugadores j on j.id = r.jugador_id where torneo_id = :torneoId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':torneoId', $torneoId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    

    public function CountPlayersByIdTournament($torneoId) {
        try {
            $query = "SELECT COUNT(jugador_id) as total FROM registros WHERE torneo_id = :torneoId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':torneoId', $torneoId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($result !== false) ? $result['total'] : 0;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function deletePlayerRecordById($id, $idTournament) {
        try {
            $query = "DELETE FROM registros WHERE jugador_id = :id and torneo_id = :idTournament";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':idTournament', $idTournament, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function addPlayerRecordById() {
        try {
            $query = "INSERT INTO registros (torneo_id, jugador_id) VALUES (:torneoId, :jugadorId)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':torneoId', $this->torneoId, PDO::PARAM_INT);
            $stmt->bindParam(':jugadorId', $this->jugadorId, PDO::PARAM_INT);
            $stmt->execute();
            return true; 

        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
            return false;
        }
    }
    
    
}