<!-- Consultor Sidebar -->
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
                    <a href="{{ route('consultor.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('consultor.dashboard') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('consultor.dashboard') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Gestión de Horas -->
        <div>
            <h3 class="text-xs font-bold text-[#708090] uppercase tracking-wider mb-2 px-2">Gestión de Horas</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('consultor.horas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('consultor.horas.index') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('consultor.horas.index') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Mis Horas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('consultor.horas.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('consultor.horas.create') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('consultor.horas.create') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Registrar Horas</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Pagos -->
        <div>
            <h3 class="text-xs font-bold text-[#708090] uppercase tracking-wider mb-2 px-2">Pagos</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('consultor.pagos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('consultor.pagos.index') || request()->routeIs('consultor.pagos.show') || request()->routeIs('consultor.pagos.confirmar.*') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('consultor.pagos.index') || request()->routeIs('consultor.pagos.show') || request()->routeIs('consultor.pagos.confirmar.*') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Mis Pagos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('consultor.datos-bancarios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('consultor.datos-bancarios.*') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 {{ request()->routeIs('consultor.datos-bancarios.*') ? 'text-[#4682B4]' : 'text-[#4682B4] group-hover:text-[#FF6347]' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span>Datos Bancarios</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Empresas -->
        <div>
            <h3 class="text-xs font-bold text-[#708090] uppercase tracking-wider mb-2 px-2">Empresas</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('consultor.empresas.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ request()->routeIs('consultor.empresas.index') ? 'text-[#4682B4] bg-[#f3f7fa] shadow-sm border border-[#e3eaf2]' : 'text-[#708090] hover:text-[#FF6347] hover:bg-[#fbe9e6]' }} transition-all duration-200 group">
                        <svg class="w-5 h-5 text-[#4682B4] group-hover:text-[#FF6347] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Mis Empresas</span>
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