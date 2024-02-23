<?php
require_once('dbModel.php');

class RecetaCombinada extends BaseModel {

    private $id;
    private $id_receta_principal;
    private $id_receta_compuesta;
    private $cantidad_receta_compuesta;

    public function setPrincipal($id_receta_principal) {
        $this->id_receta_principal = $id_receta_principal;
    }

    public function setCompuesta($id_receta_compuesta) {
        $this->id_receta_compuesta = $id_receta_compuesta;
    }

    public function setCantidadCompuesta($cantidad_receta_compuesta) {
        $this->cantidad_receta_compuesta = $cantidad_receta_compuesta;
    }


    public function __construct($id_receta_principal = null, $id_receta_compuesta = null, $cantidad_receta_compuesta = null) {
        parent::__construct();
        $this->id_receta_principal = $id_receta_principal;
        $this->id_receta_compuesta = $id_receta_compuesta;
        $this->cantidad_receta_compuesta = $cantidad_receta_compuesta;
    }

    public function newRecetaCombinada() {
        try {
            $query = "INSERT INTO recetas_combinadas (id_receta_principal, id_receta_compuesta, cantidad_receta_compuesta) VALUES (:id_receta_principal, :id_receta_compuesta, :cantidad_receta_compuesta)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_receta_principal', $this->id_receta_principal, PDO::PARAM_INT);
            $stmt->bindParam(':id_receta_compuesta', $this->id_receta_compuesta, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad_receta_compuesta', $this->cantidad_receta_compuesta, PDO::PARAM_INT);
            $success = $stmt->execute();
            return $success ? true : false;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
