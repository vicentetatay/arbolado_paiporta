<?php
session_start();

// Database config
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'vicent');
define('DB_PASS', 'passwdbd');
define('DB_NAME', 'ARBOLADO');

// Database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("ConnectError: " . $conn->connect_error);
}

// Path for Logs
define('LOG_FILE', __DIR__ . '/logs/actions.log');
if (!is_dir(dirname(LOG_FILE))) {
    mkdir(dirname(LOG_FILE), 0777, true);
}

// Functions
function registerAction($action, $user) {
    $logEntry = date('[Y-m-d H\:i\:s]') . " - $user: $action\n";
    file_put_contents(LOG_FILE, $logEntry, FILE_APPEND);
}

function isLoggedIn() {
    return !empty($_SESSION['user_id']) && !empty($_SESSION['username']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}
?>
