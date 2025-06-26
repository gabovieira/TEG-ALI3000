<?php
// Servicio para obtener la tasa BCV desde una API externa
require_once __DIR__ . '/../models/TasaBCV.php';
require_once __DIR__ . '/../../config/database.php';

class TasaBCVService {
 private $db;
     private $apiUrl = 'https://ve.dolarapi.com/v1/dolares/oficial';

    public function __construct($db) {
        $this->db = $db;
    }

    // Consulta la nueva API y retorna la tasa BCV
    public function fetchTasaBCV() {
        $json = @file_get_contents($this->apiUrl);
        if ($json === false) return null;
        $data = json_decode($json, true);
        if (!isset($data['precio'])) return null;
        return $data['precio'];
    }

   
    public function saveTasaBCV($tasa, $origen = 'API') {
    $fecha = date('Y-m-d');
    $stmt = $this->db->prepare('SELECT id FROM tasas_bcv WHERE fecha_registro = ?');
    $stmt->bind_param('s', $fecha);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        // Si ya existe, actualiza la tasa y el origen
        $stmt->close();
        $stmt = $this->db->prepare('UPDATE tasas_bcv SET tasa = ?, origen = ? WHERE fecha_registro = ?');
        $stmt->bind_param('dss', $tasa, $origen, $fecha);
        $stmt->execute();
        $stmt->close();
        return true;
    }
    $stmt->close();
    // Si no existe, inserta nuevo registro
    $stmt = $this->db->prepare('INSERT INTO tasas_bcv (tasa, fecha_registro, origen) VALUES (?, ?, ?)');
    $stmt->bind_param('dss', $tasa, $fecha, $origen);
    $stmt->execute();
    $stmt->close();
    return true;
}

    // Obtiene la última tasa registrada
    public function getUltimaTasa() {
        $result = $this->db->query('SELECT * FROM tasas_bcv ORDER BY fecha_registro DESC LIMIT 1');
        if ($row = $result->fetch_assoc()) {
            return new TasaBCV($row['id'], $row['tasa'], $row['fecha_registro'], $row['origen']);
        }
        return null;
    }
}
   
