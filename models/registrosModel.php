<?php
require_once('dbModel.php');

class Registros extends BaseModel {

    protected $id;
    protected $torneoId;
    protected $jugadorId;

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

    public function deletePlayerRecordById($id) {
        try {
            $query = "DELETE FROM registros WHERE jugador_id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true; 
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    
}