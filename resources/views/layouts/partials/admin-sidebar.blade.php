<!-- Admin Sidebar -->
<aside class="w-64 bg-white shadow-lg flex flex-col sticky top-0 left-0 h-screen z-30 border-r border-gray-100 font-[Plus Jakarta Sans]">
    <!-- Logo Header - Simplificado -->
    <div class="p-6 border-b border-gray-100 bg-white flex flex-col items-center">
        <img src="{{ asset('assets/img/logoali3000.png') }}" alt="Logo" class="w-16 h-16">
    </div>

    <!-- Navigation Menu - Con scroll cuando es necesario -->
    <nav class="flex-1 p-4 space-y-6 overflow-y-auto flex flex-col" id="sidebar-nav-scroll">
        <!-- Principal -->
        <div>
            <h3 class="text-xs font-bold text-[#708090] uppercase tracking-wider mb-2 px-2">Principal</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('admin.dashboard') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Gestión de Usuarios -->
        <div>
            <h3 class="text-xs font-bold text-[#708090] uppercase tracking-wider mb-2 px-2">Gestión de Usuarios</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('admin.usuarios.*') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.usuarios.*') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tokens.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('admin.tokens.*') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.tokens.*') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        <span>Tokens de Registro</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Horas y Pagos -->
        <div>
            <h3 class="text-xs font-bold text-[#708090] uppercase tracking-wider mb-2 px-2">Horas y Pagos</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.horas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('admin.horas.*') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.horas.*') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Aprobar Horas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pagos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('admin.pagos.*') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.pagos.*') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Gestionar Pagos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pagos.generar.form') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('admin.pagos.generar.form') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.pagos.generar.form') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Generar Pagos</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Gestión de Empresas -->
        <div>
            <h3 class="text-xs font-bold text-[#708090] uppercase tracking-wider mb-2 px-2">Gestión de Empresas</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.empresas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('admin.empresas.*') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.empresas.*') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Empresas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.empresas.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('admin.empresas.create') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.empresas.create') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Nueva Empresa</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Configuración del Sistema -->
        <div>
            <h3 class="text-xs font-bold text-[#708090] uppercase tracking-wider mb-2 px-2">Sistema</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.configuracion.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('admin.configuracion.*') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.configuracion.*') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Configuración</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Espacio flexible para empujar el botón de cerrar sesión al final -->
        <div class="flex-grow"></div>
        
        <!-- Botón de cerrar sesión siempre visible al final del sidebar -->
        <div class="mt-6 pt-6 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6] transition-all duration-200 group">
                    <svg class="w-5 h-5 text-[#4682B4] group-hover:text-[#FF6347] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </nav>
</aside>