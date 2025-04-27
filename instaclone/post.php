<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = $_POST['caption'];
    $user_id = $_SESSION['user_id'];

    // Handle image upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $image_url = $target_file;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, image_url, caption) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $image_url, $caption);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error al publicar: " . $conn->error;
        }
    } else {
        echo "Error al subir la imagen";
    }
}
?>