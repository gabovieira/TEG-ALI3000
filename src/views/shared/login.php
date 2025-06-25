<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ALI 3000</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #000; }
        @keyframes fade-in { from { opacity: 0; transform: scale(0.98);} to { opacity: 1; transform: scale(1);} }
        .animate-fade-in { animation: fade-in 0.2s ease; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="flex flex-col md:flex-row w-full min-h-screen">
        <!-- Panel Izquierdo -->
        <div class="relative w-full md:w-1/2 flex flex-col justify-center bg-black text-white px-8 md:px-16 py-12 md:py-0 min-h-[300px] md:min-h-0">
            <div class="z-10 relative">
                <h1 class="text-3xl md:text-4xl font-bold mb-4">Bienvenido a ALI 3000</h1>
                <p class="mb-8 text-base md:text-lg text-gray-300">Sistema de gestión de horas y honorarios.<br>Accede con tu cuenta.</p>
            </div>
        </div>
        <!-- Panel Derecho (Formulario) -->
        <div class="w-full md:w-1/2 flex items-center justify-center bg-white py-12 md:py-0">
            <div class="w-full max-w-md p-6 md:p-10 rounded-2xl shadow-xl mx-auto">
                <div class="flex mb-6 border-b">
                    <button class="flex-1 py-2 font-semibold border-b-2 border-black">Iniciar Sesión</button>
                    <button onclick="window.location='index.php?controller=auth&action=showRegister'" class="flex-1 py-2 text-gray-400">Registrarse</button>
                </div>
                <?php if (isset($success)) : ?>
                    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-center">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($error)) : ?>
                    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="index.php?controller=auth&action=loginUser" class="space-y-5">
                    <div>
                        <label class="block text-gray-700 mb-1" for="usuario">Correo electrónico o Código de Usuario</label>
                        <input type="text" id="usuario" name="usuario" required autofocus class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1" for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end">
                        <a href="#" class="text-blue-600 text-sm">¿Olvidaste tu contraseña?</a>
                    </div>
                    <button type="submit" class="w-full bg-black hover:bg-gray-900 text-white font-bold py-3 rounded-lg transition">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </div>
    <?php if (isset($nombre_usuario_exito) && $nombre_usuario_exito): ?>
    <script>
    window.addEventListener('DOMContentLoaded', function() {
        let alerta = document.createElement('div');
        alerta.className = 'fixed bottom-8 right-8 z-50 bg-green-100 border border-green-300 text-green-800 rounded-xl px-6 py-4 shadow-lg flex flex-col items-center max-w-xs animate-fade-in';
        alerta.innerHTML = `
            <div class='text-lg font-bold mb-1'>¡Registro exitoso!</div>
            <div class='mb-2'>Tu nombre de usuario es:</div>
            <div class='text-2xl font-mono font-bold bg-white px-4 py-2 rounded border border-green-200 mb-2'><?php echo htmlspecialchars($nombre_usuario_exito); ?></div>
            <div class='mb-2 text-gray-700 text-sm'>Guárdalo para iniciar sesión junto a tu contraseña.</div>
            <button onclick='this.parentNode.remove()' class='absolute top-2 right-3 text-gray-400 hover:text-red-500 text-2xl font-bold'>&times;</button>
        `;
        document.body.appendChild(alerta);
        setTimeout(() => { alerta.remove(); }, 20000); // 20 segundos
    });
    </script>
    <?php endif; ?>
</body>
</html>
