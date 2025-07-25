<!-- Consultor Header -->
<header class="bg-white border-b border-gray-100 sticky top-0 z-20">
    <div class="px-6 py-4 flex justify-between items-center">
        <!-- Page Title -->
        <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        
        <!-- Right Side Actions -->
        <div class="flex items-center space-x-4">
            <!-- User Info -->
            <div class="flex items-center">
                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                    {{ substr(auth()->user()->primer_nombre, 0, 1) }}{{ substr(auth()->user()->primer_apellido, 0, 1) }}
                </div>
            </div>
        </div>
    </div>
</header>