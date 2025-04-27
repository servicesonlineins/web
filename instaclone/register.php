<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $username = explode('@', $email)[0];

    $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $username, $password);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        header("Location: index.php");
        exit;
    } else {
        echo "Error al registrar: " . $conn->error;
    }
}
?>