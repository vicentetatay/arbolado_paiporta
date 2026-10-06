<?php
require_once 'config.php';

$user = $_SESSION['username'] ?? 'desconocido';
registerAction('User logged out', $user);

session_destroy();
header('Location: login.php');
exit();
?>
