<!-- Admin Header -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Left Side -->
        <div class="flex items-center space-x-4">
            <!-- Mobile menu button -->
            <button type="button" class="md:hidden text-[#708090] hover:text-[#4682B4]">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            
            <!-- Page Title -->
            <h1 class="text-xl font-semibold text-[#000000]">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>
        
        <!-- Right Side -->
        <div class="flex items-center space-x-4">
            <!-- Espacio para otros elementos si se necesitan en el futuro -->
            
            <!-- User Menu - Simplificado -->
            <div class="relative">
                <div class="w-8 h-8 bg-gradient-to-tr from-[#FF6347] to-[#4682B4] rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-bold">AD</span>
                </div>
            </div>
        </div>
    </div>
</header>