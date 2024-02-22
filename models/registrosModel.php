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
            date_default_timezone_set('America/Costa_Rica');
            $hora_actual = gmdate("Y-m-d", time() - 6 * 3600);

            $query = "SELECT DISTINCT t.id_tipo_registro, t.nombre_tipo FROM tipos_registro_horarios t WHERE t.id_tipo_registro NOT IN (SELECT DISTINCT rh.tipo FROM registros_horarios rh WHERE rh.id_usuario = :id_usuario AND DATE_FORMAT(rh.hora, '%Y-%m-%d') = DATE_FORMAT(:hora_actual, '%Y-%m-%d'))";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':hora_actual', $hora_actual, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getTimesByUserId() {
        try {
            date_default_timezone_set('America/Costa_Rica');
            $hora_actual = gmdate("Y-m-d", time() - 6 * 3600);
            
            $query = "SELECT rh.hora, trp.nombre_tipo
                        FROM registros_horarios rh
                        INNER JOIN tipos_registro_horarios trp
                        ON rh.tipo = trp.id_tipo_registro
                        WHERE id_usuario = :id_usuario
                        AND DATE(hora) = :hora_actual;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':hora_actual', $hora_actual, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getTImeByDay() {
        try {
            date_default_timezone_set('America/Costa_Rica');
            $hora_actual = gmdate("Y-m-d", time() - 6 * 3600);
            $query = "SELECT 
                        TIMEDIFF(almuerzo_inicio.hora, entrada.hora) AS antes_almuerzo,
                        TIMEDIFF(almuerzo_fin.hora, almuerzo_inicio.hora) AS tiempo_almuerzo,
                        TIMEDIFF(salida.hora, almuerzo_fin.hora) AS despues_almuerzo,
                        ADDTIME(
                            TIMEDIFF(almuerzo_inicio.hora, entrada.hora), 
                            TIMEDIFF(salida.hora, almuerzo_fin.hora)
                        ) AS total_horas_trabajadas
                    FROM 
                        (SELECT hora FROM registros_horarios WHERE id_usuario = :id_usuario AND DATE(hora) = :hora_actual AND tipo = 1) AS entrada,
                        (SELECT hora FROM registros_horarios WHERE id_usuario = :id_usuario AND DATE(hora) = :hora_actual AND tipo = 2) AS almuerzo_inicio,
                        (SELECT hora FROM registros_horarios WHERE id_usuario = :id_usuario AND DATE(hora) = :hora_actual AND tipo = 3) AS almuerzo_fin,
                        (SELECT hora FROM registros_horarios WHERE id_usuario = :id_usuario AND DATE(hora) = :hora_actual AND tipo = 4) AS salida;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':hora_actual', $hora_actual, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getTimeByWeek() {
        try {
            date_default_timezone_set('America/Costa_Rica');
            $currentWeek = gmdate("Y-m-d", time() - 6 * 3600);
            $query = "SELECT 
                        SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, almuerzo_inicio.hora, almuerzo_fin.hora))) AS total_tiempo_almuerzo,
                        SEC_TO_TIME(SUM(
                            TIMESTAMPDIFF(SECOND, entrada.hora, almuerzo_inicio.hora) + 
                            TIMESTAMPDIFF(SECOND, almuerzo_fin.hora, salida.hora)
                        )) AS total_horas_trabajadas
                    FROM 
                        registros_horarios AS entrada
                    JOIN registros_horarios AS almuerzo_inicio ON entrada.id_usuario = almuerzo_inicio.id_usuario AND DATE(entrada.hora) = DATE(almuerzo_inicio.hora) AND entrada.tipo = 1 AND almuerzo_inicio.tipo = 2
                    JOIN registros_horarios AS almuerzo_fin ON entrada.id_usuario = almuerzo_fin.id_usuario AND DATE(entrada.hora) = DATE(almuerzo_fin.hora) AND entrada.tipo = 1 AND almuerzo_fin.tipo = 3
                    JOIN registros_horarios AS salida ON entrada.id_usuario = salida.id_usuario AND DATE(entrada.hora) = DATE(salida.hora) AND entrada.tipo = 1 AND salida.tipo = 4
                    WHERE 
                        entrada.id_usuario = :id_usuario AND WEEK(entrada.hora) = WEEK(:currentWeek)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':currentWeek', $currentWeek, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getAllTimeWeeks() {
        try {
            date_default_timezone_set('America/Costa_Rica');
            $currentWeek = gmdate("Y-m-d", time() - 6 * 3600);
            $query = "SELECT rh.*, trh.nombre_tipo FROM registros_horarios rh INNER JOIN tipos_registro_horarios trh ON rh.tipo = trh.id_tipo_registro WHERE WEEK(hora) = WEEK(:currentWeek) && id_usuario = :id_usuario ORDER BY rh.hora ;";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':currentWeek', $currentWeek, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
        
}