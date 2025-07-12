<?php
// Controlador de autenticación para ALI 3000
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    // Mostrar formulario de login
    public function showLogin() {
        include __DIR__ . '/../views/compartido/login.php';
    }


    // Procesar login de administrador
    public function loginAdmin() {
        $codigo = $_POST['codigo_usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = $this->userModel->loginAdmin($codigo, $password);
        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            header('Location: index.php?controller=admin&action=dashboard');
            exit;
        } else {
            $error = 'Código o contraseña incorrectos';
            include __DIR__ . '/../views/compartido/login.php';
        }
    }

    // Procesar login de usuario (consultor/validador)
    public function loginUser() {
        $input = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = $this->userModel->loginUser($input, $password);
        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            // Redirigir según el tipo de usuario
            if ($user['tipo_usuario'] === 'admin') {
                header('Location: index.php?controller=admin&action=dashboard');
            } elseif ($user['tipo_usuario'] === 'consultor') {
                header('Location: index.php?controller=consultor&action=dashboard');
            } elseif ($user['tipo_usuario'] === 'validador') {
                header('Location: index.php?controller=validador&action=dashboard');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $error = 'Usuario/email o contraseña incorrectos';
            include __DIR__ . '/../views/compartido/login.php';
        }
    }

    // Logout
    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    // Mostrar formulario de registro (consultor/validador)
    public function showRegister() {
        include __DIR__ . '/../views/shared/register.php';
    }

    // Procesar registro inicial (consultor/validador)
    public function registerUser() {
        $codigo = $_POST['codigo_usuario'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if ($this->userModel->setEmailAndPassword($codigo, $email, $password)) {
            $user = $this->userModel->findByCode($codigo);
            $nombre_usuario = $user['primer_nombre'] && $user['primer_apellido']
                ? strtoupper(mb_substr(trim($user['primer_nombre']), 0, 1, 'UTF-8')) . strtoupper(str_replace(' ', '', trim($user['primer_apellido'])))
                : '';
            $success = 'Registro exitoso. Ahora puedes iniciar sesión.';
            $nombre_usuario_exito = $nombre_usuario;
            include __DIR__ . '/../views/shared/login.php';
        } else {
            $error = 'Código inválido o ya registrado.';
            include __DIR__ . '/../views/shared/register.php';
        }
    }

    // API para autocompletar datos por código de registro
    public function apiGetUserByCode() {
        $codigo = $_GET['codigo_usuario'] ?? '';
        $user = $this->userModel->findByCode($codigo);
        if ($user) {
            header('Content-Type: application/json');
            echo json_encode([
                'existe' => true,
                'cedula' => $user['cedula'] ?? '',
                'nombre' => $user['primer_nombre'] ?? '',
                'apellido' => $user['primer_apellido'] ?? ''
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['existe' => false]);
        }
        exit;
    }
}
