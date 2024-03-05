<?php
require_once('dbModel.php');

class UsuarioModel extends BaseModel {


    private $id;
    private $nickname;
    private $email;
    private $cedula;
    private $telefono_usuario;
    private $fecha_nacimiento;
    private $fecha_ingreso;
    private $fecha_salida;
    private $role;
    private $password;
    private $habilitado;

    public function setId($id){
        $this->id = $id;
    }

    public function setNickName($nickname){
        $this->nickname = $nickname;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function setRole($role){
        $this->role = $role;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function setHabilitado($habilitado){
        $this->habilitado = $habilitado;
    }

    public function setCedula($cedula) {
        $this->cedula = $cedula;
    }

    public function setTelefonoUsuario($telefono_usuario) {
        $this->telefono_usuario = $telefono_usuario;
    }

    public function setFechaNacimiento($fecha_nacimiento) {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function setFechaIngreso($fecha_ingreso) {
        $this->fecha_ingreso = $fecha_ingreso;
    }

    public function setFechaSalida($fecha_salida) {
        $this->fecha_salida = $fecha_salida;
    }

    public function __construct($id = null, $nickname = null, $email = null,$role = null,$password = null,$habilitado = null, $cedula = null, $telefono_usuario = null, $fecha_nacimiento = null, $fecha_ingreso = null, $fecha_salida = null ) {
        parent::__construct();
        $this->id = $id;
        $this->nickname = $nickname;
        $this->email = $email;
        $this->role = $role;
        $this->password = $password;
        $this->habilitado = $habilitado;
        $this->cedula = $cedula;
        $this->telefono_usuario = $telefono_usuario;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->fecha_ingreso = $fecha_ingreso;
        $this->fecha_salida = $fecha_salida;
    }

    public function getAllUsuarios(){
        try {
            $query = "SELECT * from usuarios";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function updatePasswordById() {
        try {
            $query = "UPDATE usuarios SET password = :password WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
            $success = $stmt->execute();
            return $success ? true : false;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    } 

    public function updateRoleById() {
        try {
            $query = "UPDATE usuarios SET role = :role WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':role', $this->role, PDO::PARAM_STR);
            $success = $stmt->execute();
            return $success ? true : false;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    } 

    public function habilitarDesahabilitarUsuarioById() {
        try {
            $query = "UPDATE usuarios SET habilitado = :habilitado WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':habilitado', $this->habilitado, PDO::PARAM_INT);
            $success = $stmt->execute();
            return $success ? true : false;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    } 

    public function newUsuario(){
        try {
            // Verificar si el nickname ya está registrado
            $queryNickname = "SELECT COUNT(*) AS count FROM usuarios WHERE nickname = :nickname";
            $stmtNickname = $this->conn->prepare($queryNickname);
            $stmtNickname->bindParam(':nickname', $this->nickname, PDO::PARAM_STR);
            $stmtNickname->execute();
            $resultNickname = $stmtNickname->fetch(PDO::FETCH_ASSOC);
    
            // Verificar si el email ya está registrado
            $queryEmail = "SELECT COUNT(*) AS count FROM usuarios WHERE email = :email";
            $stmtEmail = $this->conn->prepare($queryEmail);
            $stmtEmail->bindParam(':email', $this->email, PDO::PARAM_STR);
            $stmtEmail->execute();
            $resultEmail = $stmtEmail->fetch(PDO::FETCH_ASSOC);
    
            // Verificar si el nickname o el email ya están registrados
            if ($resultNickname['count'] > 0 && $resultEmail['count'] > 0) {
                return 100;
            } elseif ($resultNickname['count'] > 0) {
                return 101;
            } elseif ($resultEmail['count'] > 0) {
                return 102;
            } else {
                // Si no hay conflictos, procede con la inserción
                $queryInsert = "INSERT INTO usuarios (nickname, email, cedula, telefono_usuario, fecha_nacimiento, fecha_ingreso, role, password) VALUES(:nickname,:email,:cedula, :telefono_usuario, :fecha_nacimiento, :fecha_ingreso, :role,:password)";
                $stmtInsert = $this->conn->prepare($queryInsert);
                $stmtInsert->bindParam(':nickname', $this->nickname, PDO::PARAM_STR);
                $stmtInsert->bindParam(':email', $this->email, PDO::PARAM_STR);
                $stmtInsert->bindParam(':cedula', $this->cedula, PDO::PARAM_STR);
                $stmtInsert->bindParam(':telefono_usuario', $this->telefono_usuario, PDO::PARAM_STR);
                $stmtInsert->bindParam(':fecha_nacimiento', $this->fecha_nacimiento, PDO::PARAM_STR);
                $stmtInsert->bindParam(':fecha_ingreso', $this->fecha_ingreso, PDO::PARAM_STR);
                $stmtInsert->bindParam(':role', $this->role, PDO::PARAM_INT);
                $stmtInsert->bindParam(':password', $this->password, PDO::PARAM_STR);
                $success = $stmtInsert->execute();
                return $success ? true : false;
            }
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }   
}