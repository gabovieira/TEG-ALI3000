<?php

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'tu_base_de_datos'; // Reemplaza con el nombre de tu base de datos
$username = 'root'; // Reemplaza con tu usuario de MySQL
$password = ''; // Reemplaza con tu contraseña de MySQL

try {
    // Crear conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Consulta para obtener los pagos
    $stmt = $pdo->query('SELECT * FROM pagos');
    $pagos = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    echo "=== PAGOS EN LA BASE DE DATOS ===\n";
    if (empty($pagos)) {
        echo "No hay pagos en la base de datos.\n";
    } else {
        echo "ID\tUsuario\tEstado\t\tTotal\t\tCreado\n";
        echo str_repeat("-", 80) . "\n";
        
        foreach ($pagos as $pago) {
            echo "{$pago->id}\t";
            echo "{$pago->usuario_id}\t";
            echo str_pad($pago->estado, 10, ' '). "\t";
            echo number_format($pago->total, 2, ',', '.') . "\t";
            echo $pago->created_at . "\n";
        }
    }
    
    // Verificar relaciones
    echo "\n=== VERIFICANDO RELACIONES ===\n";
    
    // Verificar si hay datos en pago_detalles
    $stmt = $pdo->query('SELECT pago_id, COUNT(*) as total FROM pago_detalles GROUP BY pago_id');
    $detalles = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo "Detalles de pagos encontrados: " . count($detalles) . " pagos con detalles.\n";
    
    // Verificar si hay usuarios con pagos
    $stmt = $pdo->query('SELECT u.id, u.primer_nombre, u.primer_apellido, COUNT(p.id) as total_pagos 
                         FROM usuarios u 
                         LEFT JOIN pagos p ON u.id = p.usuario_id 
                         WHERE p.id IS NOT NULL 
                         GROUP BY u.id, u.primer_nombre, u.primer_apellido');
    $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    echo "Usuarios con pagos: " . count($usuarios) . "\n";
    foreach ($usuarios as $usuario) {
        echo "- {$usuario->primer_nombre} {$usuario->primer_apellido} (ID: {$usuario->id}): {$usuario->total_pagos} pagos\n";
    }
    
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
