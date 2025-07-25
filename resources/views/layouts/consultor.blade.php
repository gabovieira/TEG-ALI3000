<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Consultor - ALI3000')</title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

        .font-family-jakarta {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        :root {
            --ali-blue: #4682B4;
            --ali-orange: #FF6347;
            --ali-gray: #708090;
            --ali-dark: #000000;
        }
        
        .bg-ali-blue { background-color: var(--ali-blue); }
        .bg-ali-orange { background-color: var(--ali-orange); }
        .text-ali-blue { color: var(--ali-blue); }
        .text-ali-orange { color: var(--ali-orange); }
        .text-ali-gray { color: var(--ali-gray); }
        .border-ali-blue { border-color: var(--ali-blue); }
        
        /* Estilos personalizados para la barra de desplazamiento */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--ali-blue);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--ali-orange);
        }
        
        /* Para Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: var(--ali-blue) #f1f1f1;
        }
    </style>
</head>
<body class="bg-gray-50 font-family-jakarta">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.partials.consultor-sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 overflow-auto" id="main-content-scroll">
            <!-- Header -->
            @include('layouts.partials.consultor-header')
            
            <!-- Page Content -->
            <main class="p-6">
                @yield('consultor-content')
            </main>
            
            <!-- Footer con botón de cerrar sesión para pantallas muy altas -->
            <div class="p-4 border-t border-gray-100 bg-white md:hidden">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-[#f3f7fa] text-[#4682B4] rounded-lg hover:bg-[#FF6347] hover:text-white transition-all duration-200 text-sm font-medium flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Asegurarse de que la barra de desplazamiento sea visible en pantallas altas
        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.getElementById('main-content-scroll');
            const sidebarNav = document.getElementById('sidebar-nav-scroll');
            
            // Función para verificar si el contenido necesita scroll
            function checkScroll() {
                // Para el contenido principal
                const contentHeight = mainContent.scrollHeight;
                const viewportHeight = window.innerHeight;
                
                // Si el contenido es más alto que la ventana, asegurarse de que la barra de desplazamiento sea visible
                if (contentHeight > viewportHeight) {
                    mainContent.style.overflowY = 'scroll';
                } else {
                    mainContent.style.overflowY = 'auto';
                }
                
                // Para el sidebar
                if (sidebarNav) {
                    const sidebarHeight = sidebarNav.scrollHeight;
                    const sidebarViewportHeight = sidebarNav.clientHeight;
                    
                    // Si el contenido del sidebar es más alto que su contenedor, asegurarse de que la barra de desplazamiento sea visible
                    if (sidebarHeight > sidebarViewportHeight) {
                        sidebarNav.style.overflowY = 'scroll';
                    } else {
                        sidebarNav.style.overflowY = 'auto';
                    }
                }
            }
            
            // Verificar al cargar y al cambiar el tamaño de la ventana
            checkScroll();
            window.addEventListener('resize', checkScroll);
            
            // Hacer que la barra de desplazamiento sea siempre visible en el sidebar
            if (sidebarNav) {
                sidebarNav.style.overflowY = 'scroll';
            }
        });
    </script>
</body>
</html>