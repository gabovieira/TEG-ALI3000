<?php
// Modelo para métricas del dashboard admin
class MetricasAdmin {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener conteo de usuarios activos (consultores y validadores, excluye admin)
    public function obtenerUsuariosActivos() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuarios WHERE estado = 'activo' AND tipo_usuario IN ('consultor', 'validador')");
        return $stmt ? $stmt->fetchColumn() : 0;
    }

    // Obtener conteo de consultores activos
    public function obtenerConsultoresActivos() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo_usuario = 'consultor' AND estado = 'activo'");
        return $stmt ? $stmt->fetchColumn() : 0;
    }

    // Obtener conteo de validadores activos
    public function obtenerValidadoresActivos() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo_usuario = 'validador' AND estado = 'activo'");
        return $stmt ? $stmt->fetchColumn() : 0;
    }
}
