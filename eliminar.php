<?php
require_once 'config.php';
requireLogin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit();
}

$usuario = $_SESSION['username'];

$sql = "DELETE FROM arboles WHERE id = " . (int)$id;
if ($conn->query($sql)) {
    registerAction("Tree Deleted: ID $id", $usuario);
    header('Location: index.php');
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>
