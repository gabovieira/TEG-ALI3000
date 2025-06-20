<?php
// Punto de entrada principal para ALI 3000
// Carga la configuración y enruta la petición básica

require_once __DIR__ . '/config/database.php';

// Cargar controladores
function loadController($name, $pdo) {
    $file = __DIR__ . "/src/controllers/" . ucfirst($name) . "Controller.php";
    if (file_exists($file)) {
        require_once $file;
        $class = ucfirst($name) . 'Controller';
        return new $class($pdo);
    }
    return null;
}

session_start();
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'showLogin';

$ctrl = loadController($controller, $pdo);
if ($ctrl && method_exists($ctrl, $action)) {
    $ctrl->$action();
    exit;
}

// Si no existe el controlador o acción, mostrar login general por defecto
$ctrl = loadController('auth', $pdo);
$ctrl->showLogin();
