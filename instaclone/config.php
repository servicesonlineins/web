<?php
$host = 'localhost';
$dbname = 'instaclone';
$username = 'root';
$password = 'Jav-21*04C';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>