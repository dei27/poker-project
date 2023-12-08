<?php
function connectDB()
{
    $host = "localhost";
    $database = "poker";
    $username = "root";
    $password = "";

    try {
        $dsn = "mysql:host=$host;dbname=$database";
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
