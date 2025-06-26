<?php
// Ejemplo de cómo usar el loader en tu aplicación PHP

// Si necesitas mostrar el loader antes de procesar datos
if (isset($_GET['loading'])) {
    include 'loader.php';
    exit;
}

// Tu lógica PHP normal aquí
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ali3000 Consultores</title>
</head>
<body>
    <h1>Bienvenido a ali3000 Consultores</h1>
    
    <!-- Botón para mostrar el loader -->
    <button onclick="showLoader()">Mostrar Loader</button>
    
    <script>
        function showLoader() {
            window.location.href = '?loading=true';
        }
    </script>
</body>
</html>
