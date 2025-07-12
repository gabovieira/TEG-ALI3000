<?php
// Modelo User para ALI 3000 - Solo login por email
class User {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Buscar usuario por email
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // Validar login de usuario (admin/consultor)
    public function loginUser($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && $user['estado'] === 'activo') {
            $passwordData = $this->getPasswordData($user['id']);
            if ($passwordData && password_verify($password, $passwordData['password_hash'])) {
                return $user;
            }
        }
        return false;
    }

    // Obtener datos de contraseña del usuario
    private function getPasswordData($userId) {
        $stmt = $this->pdo->prepare('SELECT valor FROM contactos_usuario WHERE usuario_id = ? AND tipo_contacto = "password" LIMIT 1');
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        if ($result) {
            return ['password_hash' => $result['valor']];
        }
        return false;
    }

    // Crear usuario consultor/validador (solo admin)
    public function createUser($data) {
        $sql = 'INSERT INTO usuarios (codigo_usuario, tipo_usuario, primer_nombre, primer_apellido, cedula, fecha_nacimiento, estado, creado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['codigo_usuario'],
            $data['tipo_usuario'],
            $data['primer_nombre'],
            $data['primer_apellido'],
            $data['cedula'],
            $data['fecha_nacimiento'],
            'activo',
            $data['creado_por']
        ]);
    }

    // Actualizar email y contraseña (para registro inicial)
    public function setEmailAndPassword($codigo_usuario, $email, $password) {
        $user = $this->findByCode($codigo_usuario);
        if (!$user) return false;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        // Generar nombre de usuario: inicial del primer nombre + todo el primer apellido (sin espacios, mayúsculas)
        $primer_nombre = trim($user['primer_nombre']);
        $primer_apellido = trim($user['primer_apellido']);
        $inicial = $primer_nombre ? strtoupper(mb_substr($primer_nombre, 0, 1, 'UTF-8')) : '';
        $apellido = $primer_apellido ? strtoupper(str_replace(' ', '', $primer_apellido)) : '';
        $nombre_usuario = $inicial . $apellido;
        $stmt = $this->pdo->prepare('UPDATE usuarios SET email = ?, password_hash = ?, nombre_usuario = ? WHERE codigo_usuario = ? AND (email IS NULL OR email = "")');
        return $stmt->execute([$email, $hash, $nombre_usuario, $codigo_usuario]);
    }

    // Generar código de usuario único (C-XXXXX o V-XXXXX)
    public function generarCodigoUsuario($tipo_usuario) {
        $prefijo = ($tipo_usuario === 'consultor') ? 'C-' : 'V-';
        $sql = "SELECT codigo_usuario FROM usuarios WHERE tipo_usuario = ? ORDER BY id DESC LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tipo_usuario]);
        $ultimo = $stmt->fetchColumn();
        $num = 1;
        if ($ultimo && preg_match('/-(\d{5})$/', $ultimo, $m)) {
            $num = intval($m[1]) + 1;
        }
        return $prefijo . str_pad($num, 5, '0', STR_PAD_LEFT);
    }

    // Crear usuario desde admin (todos los campos)
    public function crearUsuarioDesdeAdmin($data) {
        $sql = "INSERT INTO usuarios (codigo_usuario, tipo_usuario, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, cedula, estado, creado_por, tarifa_por_hora, nivel_desarrollo) VALUES (?, ?, ?, ?, ?, ?, ?, 'activo', ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['codigo_usuario'],
            $data['tipo_usuario'],
            $data['primer_nombre'],
            $data['segundo_nombre'],
            $data['primer_apellido'],
            $data['segundo_apellido'],
            $data['cedula'],
            $data['creado_por'],
            $data['tarifa_por_hora'],
            $data['nivel_desarrollo']
        ]);
    }

    // Obtener usuarios activos (consultores y validadores) con datos completos para la vista de usuarios
    public function obtenerUsuariosActivos() {
        $sql = "SELECT u.codigo_usuario, CONCAT(u.primer_nombre, ' ', u.primer_apellido) AS nombre, u.tipo_usuario, u.estado, u.fecha_creacion, e.nombre AS empresa_nombre
                FROM usuarios u
                LEFT JOIN usuario_empresas ue ON ue.usuario_id = u.id AND ue.estado = 'activa'
                LEFT JOIN empresas e ON ue.empresa_id = e.id
                WHERE u.estado = 'activo' AND (u.tipo_usuario = 'consultor' OR u.tipo_usuario = 'validador')
                ORDER BY u.fecha_creacion DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // El resto de funciones de usuario (login, registro, etc.)
    // ...
    // (Las funciones de métricas globales han sido movidas a MetricasAdmin)
}
