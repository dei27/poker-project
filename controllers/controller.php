<?php
session_start();
include('../models/db.php');

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'login') {
        $nickname = isset($_POST['nickname']) ? htmlspecialchars($_POST['nickname'], ENT_QUOTES, 'UTF-8') : '';
        $password = isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') : '';

        if (!empty($nickname) && !empty($password)) {

            $user = getUser($nickname, $password);
        
            if ($user) {
                $_SESSION['user'] = $user;
                header("Location: " . ($user['role'] == '1' ? '../views/dashboard.php' : '../views/guest.php'));
            } else {
                header("Location: ../views/login.php");
            }
        }else {
            header("Location: ../views/login.php");
        }

        
    } elseif ($action === 'logout') {
        session_destroy();
        header("Location: ../index.php");
    }
}


function getUser($nickname, $password)
{
    // Realizar la conexión a la base de datos
    $conn = connectDB();

    try {
        // Consultar la base de datos para obtener el usuario
        $query = "SELECT * FROM usuarios WHERE nickname = :nickname AND password = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nickname', $nickname);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    } finally {
        $conn = null; // Cerrar la conexión
    }
}

