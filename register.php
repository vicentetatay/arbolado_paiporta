<?php
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($conn->real_escape_string($_POST['username']));
    $email = trim($conn->real_escape_string($_POST['email']));
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($username === '' || $email === '' || $password === '' || $password_confirm === '') {
        $error = 'Todos los campos son obligatorios.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $check = $conn->query("SELECT id FROM users WHERE username = '$username' OR email = '$email' LIMIT 1");

        if ($check && $check->num_rows > 0) {
            $error = 'Ya existe ese usuario o correo electrónico.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

            if ($conn->query($sql)) {
                $success = 'Usuario registrado correctamente. Ahora puedes iniciar sesión.';
                registerAction('New user registered', $username);
            } else {
                $error = 'Error al registrar el usuario: ' . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PaiportArbolado: Registro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Registro de Usuario</h1>

    <?php if ($error): ?>
        <div style="color: red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="color: green;"><?= htmlspecialchars($success) ?></div>
        <p><a href="login.php">Ir a iniciar sesión</a></p>
    <?php else: ?>
        <form method="POST">
            <label>Usuario:</label>
            <input type="text" name="username" required><br>

            <label>Email:</label>
            <input type="email" name="email" required><br>

            <label>Contraseña:</label>
            <input type="password" name="password" required><br>

            <label>Repetir contraseña:</label>
            <input type="password" name="password_confirm" required><br>

            <button type="submit">Registrarse</button>
        </form>
    <?php endif; ?>

    <p><a href="login.php">Volver a inicio de sesión</a></p>
</body>
</html>
