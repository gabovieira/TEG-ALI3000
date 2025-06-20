<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario | ALI 3000</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #000; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="flex flex-col md:flex-row w-full min-h-screen">
        <!-- Panel Izquierdo -->
        <div class="relative w-full md:w-1/2 flex flex-col justify-center bg-black text-white px-8 md:px-16 py-12 md:py-0 min-h-[300px] md:min-h-0">
            <div class="z-10 relative">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">Regístrate en ALI 3000</h1>
                <p class="mb-8 text-base md:text-lg text-gray-300">Ingresa el código proporcionado por el administrador para crear tu cuenta.</p>
            </div>
        </div>
        <!-- Panel Derecho (Formulario) -->
        <div class="w-full md:w-1/2 flex items-center justify-center bg-white py-12 md:py-0">
            <div class="w-full max-w-md p-6 md:p-10 rounded-2xl shadow-xl mx-auto">
                <div class="flex mb-6 border-b">
                    <button onclick="window.location='index.php'" class="flex-1 py-2 text-gray-400">Iniciar Sesión</button>
                    <button class="flex-1 py-2 font-semibold border-b-2 border-black">Registrarse</button>
                </div>
                <h2 class="text-xl md:text-2xl font-bold mb-6">Registro de Usuario</h2>
                <?php if (isset($error)) : ?>
                    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="index.php?controller=auth&action=registerUser" class="space-y-5">
                    <div>
                        <label class="block text-gray-700 mb-1" for="codigo_usuario">Código de Registro</label>
                        <input type="text" id="codigo_usuario" name="codigo_usuario" required autofocus class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1" for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1" for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-black hover:bg-gray-900 text-white font-bold py-3 rounded-lg transition">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
