<?php
// Controlador para el panel de administración
class AdminController {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_usuario'] !== 'admin') {
            header('Location: index.php');
            exit;
        }
        $admin = $_SESSION['user'];
        require_once __DIR__ . '/../models/MetricasAdmin.php';
        $metricas = new MetricasAdmin($this->pdo);
        $usuarios_activos = $metricas->obtenerUsuariosActivos();
        $consultores_activos_sidebar = $metricas->obtenerConsultoresActivos();
        $validadores_activos = $metricas->obtenerValidadoresActivos();
        // Empresas activas
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM empresas WHERE estado = 'activo'");
        $empresas_activas = $stmt ? $stmt->fetchColumn() : 0;
        include __DIR__ . '/../views/admin/dashboard.php';
    }

    // Crear usuario desde el dashboard admin
    public function crearUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../models/User.php';
            $userModel = new User($this->pdo);
            // Recoger y limpiar datos del formulario
            $primer_nombre = trim($_POST['primer_nombre'] ?? '');
            $segundo_nombre = trim($_POST['segundo_nombre'] ?? '');
            $primer_apellido = trim($_POST['primer_apellido'] ?? '');
            $segundo_apellido = trim($_POST['segundo_apellido'] ?? '');
            $cedula = trim($_POST['cedula'] ?? '');
            $rif = trim($_POST['rif'] ?? '');
            $sexo = trim($_POST['sexo'] ?? '');
            $tipo_usuario = trim($_POST['tipo_usuario'] ?? '');
            $tarifa_por_hora = isset($_POST['tarifa_por_hora']) ? floatval($_POST['tarifa_por_hora']) : null;
            $nivel_desarrollo = trim($_POST['nivel_desarrollo'] ?? '');
            $empresa = trim($_POST['empresa'] ?? '');
            $creado_por = $_SESSION['user']['id'] ?? null;

            // Generar código de usuario
            $codigo_usuario = $userModel->generarCodigoUsuario($tipo_usuario);

            // Insertar usuario
            $data = [
                'codigo_usuario' => $codigo_usuario,
                'tipo_usuario' => $tipo_usuario,
                'primer_nombre' => $primer_nombre,
                'segundo_nombre' => $segundo_nombre,
                'primer_apellido' => $primer_apellido,
                'segundo_apellido' => $segundo_apellido,
                'cedula' => $cedula,
                'rif' => $rif,
                'sexo' => $sexo,
                'tarifa_por_hora' => $tarifa_por_hora,
                'nivel_desarrollo' => $nivel_desarrollo,
                'empresa' => $empresa,
                'creado_por' => $creado_por
            ];
            $exito = $userModel->crearUsuarioDesdeAdmin($data);
            if ($exito) {
                // Redirigir o mostrar mensaje de éxito
                header('Location: index.php?controller=admin&action=dashboard&exito=1&codigo='.$codigo_usuario);
                exit;
            } else {
                // Redirigir o mostrar error
                header('Location: index.php?controller=admin&action=dashboard&error=1');
                exit;
            }
        }
        // Si no es POST, redirigir al dashboard
        header('Location: index.php?controller=admin&action=dashboard');
        exit;
    }

    // Vista de gestión de usuarios (consultores y validadores)
    public function usuarios() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['tipo_usuario'] !== 'admin') {
            header('Location: index.php');
            exit;
        }
        require_once __DIR__ . '/../models/User.php';
        $userModel = new User($this->pdo);
        $usuarios_activos = $userModel->obtenerUsuariosActivos();
        include __DIR__ . '/../views/admin/usuarios.php';
    }
}
