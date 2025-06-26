<?php
// Sidebar reutilizable para admin
?>
<aside class="bg-white border-r border-gray-200 w-64 min-h-screen flex flex-col py-6 px-4">
    <div class="flex-1 flex flex-col justify-between h-full">
        <div>
            <div class="flex items-center mb-8">
                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center mr-3">
                    <img src="/ali3000/assets/img/logoali3000.png" alt="Logo ALI 3000"  />
                </div>
                <div>
                    <div class="text-lg font-bold text-gray-900">ALI 3000</div>
                    <div class="text-xs text-gray-400">Sistema de Gestión</div>
                </div>
            </div>
            <nav class="space-y-1">
                <a href="index.php?controller=admin&action=dashboard" class="flex items-center px-3 py-2 rounded-lg <?php echo ($menu_activo === 'dashboard') ? 'bg-gray-100 text-gray-900 font-semibold' : 'hover:bg-gray-100 transition'; ?>">
                    <!-- Home (Lucide) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 lucide lucide-home" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M3 9.5 12 4l9 5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 10v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 22V12h6v10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Dashboard
                </a>
                <div class="text-xs text-gray-400 mt-4 mb-1 ml-3">Gestión de Usuarios</div>
                <a href="index.php?controller=admin&action=usuarios" class="flex items-center px-3 py-2 rounded-lg <?php echo ($menu_activo === 'usuarios') ? 'bg-gray-100 text-gray-900 font-semibold' : 'hover:bg-gray-100 transition'; ?>">
                    <!-- Users (Lucide) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 lucide lucide-user" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 20v-2a4 4 0 0 1 4-4h0a4 4 0 0 1 4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Usuarios
                    <span class="ml-auto text-xs bg-gray-200 text-gray-700 rounded-full px-2 py-0.5 font-bold" title="Solo consultores y validadores activos"><?php echo isset($usuarios_activos) ? count($usuarios_activos) : 0; ?></span>
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    <!-- User Cog (Lucide) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 lucide lucide-users" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Consultores
                    <span class="ml-auto text-xs bg-gray-200 text-gray-700 rounded-full px-2 py-0.5 font-bold">--</span>
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    <!-- Shield (Lucide) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 lucide lucide-shield" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Validadores
                    <span class="ml-auto text-xs bg-gray-200 text-gray-700 rounded-full px-2 py-0.5 font-bold">--</span>
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    <!-- Building (Lucide) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 lucide lucide-building" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16 3v4M8 3v4M3 10h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Empresas
                    <span class="ml-auto text-xs bg-gray-200 text-gray-700 rounded-full px-2 py-0.5 font-bold">--</span>
                </a>
                <div class="text-xs text-gray-400 mt-4 mb-1 ml-3">Operaciones</div>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    <!-- Clock (Lucide) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 lucide lucide-clock" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <polyline points="12 6 12 12 16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Gestión de Horas
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    <!-- Credit Card (Lucide) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 lucide lucide-credit-card" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <line x1="2" y1="10" x2="22" y2="10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Pagos
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    <!-- Bar Chart (Lucide) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 lucide lucide-bar-chart-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M3 12v8h18v-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="7" y="8" width="3" height="8" rx="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="14" y="4" width="3" height="12" rx="1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Reportes
                </a>
                <div class="text-xs text-gray-400 mt-4 mb-1 ml-3">Sistema</div>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    <!-- Settings (Lucide) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 lucide lucide-settings" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Configuración
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-100 transition relative">
                    <!-- Bell (Lucide) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3 lucide lucide-bell" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Notificaciones
                    <span class="ml-auto text-xs bg-red-500 text-white rounded-full px-2 py-0.5 font-bold">3</span>
                </a>
            </nav>
        </div>
        <div class="mt-8">
            <div class="flex items-center bg-gray-100 rounded-lg px-3 py-2">
                <div class="w-8 h-8 rounded bg-gray-300 flex items-center justify-center mr-2">
                    <span class="text-gray-700 font-bold">AD</span>
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-900">Admin</div>
                    <div class="text-xs text-gray-500">admin@ali3000.com</div>
                </div>
            </div>
            <a href="index.php?controller=auth&action=logout" class="block mt-3 text-xs text-red-500 hover:underline text-center">Cerrar sesión</a>
        </div>
    </div>
</aside>
