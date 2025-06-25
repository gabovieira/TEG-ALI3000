<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Consultor | ALI 3000</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 border border-blue-100 flex flex-col items-center">
        <h1 class="text-2xl font-bold text-green-700 mb-4">¡Has iniciado sesión con éxito!</h1>
        <p class="mb-8 text-gray-700">Bienvenido al panel de Consultor.</p>
        <a href="index.php?controller=auth&action=logout" class="bg-red-500 hover:bg-red-600 text-white font-bold px-6 py-3 rounded-lg shadow transition">Cerrar sesión</a>
    </div>
</body>
</html>
