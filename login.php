<?php
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($conn->real_escape_string($_POST['username']));
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $error = 'Debes introducir usuario y contraseña.';
    } else {
        $sql = "SELECT id, username, password FROM users WHERE username = '$username' LIMIT 1";
        $result = $conn->query($sql);

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                registerAction('User logged in', $user['username']);
                header('Location: index.php');
                exit();
            }
        }

        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PaiportArbolado: Iniciar Sesión</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <?php if ($error): ?>
        <div style="color: red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Usuario:</label>
        <input type="text" name="username" required><br>

        <label>Contraseña:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Entrar</button>
    </form>

    <p>¿No tienes cuenta? <a href="register.php">Regístrate</a></p>
</body>
</html>
