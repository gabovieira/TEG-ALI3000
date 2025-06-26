<?php
// Vista: Gestión de Usuarios (CRUD) para Admin
// Requiere: $usuarios_activos (array de usuarios activos consultores/validadores)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios | ALI 3000</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">
<?php include __DIR__ . '/../compartido/loader.php'; ?>
<?php $menu_activo = 'usuarios'; $titulo_header = 'Usuarios'; ?>
<div class="flex min-h-screen h-screen overflow-hidden">
    <?php include __DIR__ . '/../compartido/sidebar-admin.php'; ?>
    <main class="flex-1 flex flex-col min-h-0 overflow-y-auto">
        <?php include __DIR__ . '/../compartido/header-admin.php'; ?>
        <div class="flex-1 p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Usuarios Activos (Consultores y Validadores)</h2>
            <div class="overflow-x-auto rounded-lg shadow mb-8">
                <table class="min-w-full bg-white text-gray-800 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Código</th>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Tipo</th>
                            <th class="px-4 py-2 text-left">Empresa</th>
                            <th class="px-4 py-2 text-left">Estado</th>
                            <th class="px-4 py-2 text-left">Creado</th>
                            <th class="px-4 py-2 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($usuarios_activos)): foreach ($usuarios_activos as $usuario): ?>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-4 py-2"><?php echo htmlspecialchars($usuario['codigo_usuario']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td class="px-4 py-2 capitalize"><?php echo htmlspecialchars($usuario['tipo_usuario']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($usuario['empresa_nombre'] ?? ''); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($usuario['estado']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($usuario['fecha_creacion']); ?></td>
                            <td class="px-4 py-2 text-center flex gap-2 justify-center">
                                <button class="text-blue-600 hover:underline text-xs font-bold" title="Editar">Editar</button>
                                <button class="text-yellow-600 hover:underline text-xs font-bold" title="Desactivar">Desactivar</button>
                                <button class="text-red-600 hover:underline text-xs font-bold" title="Eliminar">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="7" class="text-center text-gray-400 py-6">Sin usuarios activos</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Aquí puedes agregar botones para crear usuario, asignar empresa, etc. -->
        </div>
    </main>
</div>
<script>
window.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var loader = document.getElementById('loader');
        if(loader) {
            loader.style.opacity = '0';
            setTimeout(function() {
                loader.style.display = 'none';
            }, 200);
        }
    }, 400);
});
</script>
</body>
</html>
