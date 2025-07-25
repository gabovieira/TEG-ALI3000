<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Registro - ALI3000 Consultores</title>
    <meta name="description" content="Sistema de gestión de consultores ALI3000">

    <!-- Tailwind -->
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
    </style>
</head>
<body class="bg-white font-family-jakarta h-screen">

    <div class="w-full flex flex-wrap h-screen">

        <!-- Error Section -->
        <div class="w-full md:w-1/2 flex flex-col">

            <div class="flex justify-center md:justify-start pt-12 md:pl-12 md:-mb-24">
                <div class="flex items-center">
                    <img src="{{ asset('assets/img/logoali3000.png') }}" alt="ALI3000 Logo" class="h-16 w-auto">
                </div>
            </div>

            <div class="flex flex-col justify-center md:justify-start my-auto pt-8 md:pt-0 px-8 md:px-24 lg:px-32">
                <div class="text-center">
                    <svg class="mx-auto h-24 w-24 text-red-500 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Token de Registro Inválido</h1>
                    
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                        <p class="text-red-800 font-medium">{{ $mensaje }}</p>
                        @if(isset($detalle))
                            <div class="mt-4 p-4 bg-gray-100 rounded text-left overflow-auto max-h-64 text-xs">
                                <pre>{{ $detalle }}</pre>
                            </div>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        <p class="text-ali-gray">Posibles causas:</p>
                        <ul class="text-left text-ali-gray space-y-2 max-w-md mx-auto">
                            <li class="flex items-start">
                                <span class="text-red-500 mr-2">•</span>
                                El token ha expirado
                            </li>
                            <li class="flex items-start">
                                <span class="text-red-500 mr-2">•</span>
                                El token ya fue utilizado
                            </li>
                            <li class="flex items-start">
                                <span class="text-red-500 mr-2">•</span>
                                El enlace no es válido
                            </li>
                        </ul>
                    </div>
                    
                    <div class="mt-8 space-y-4">
                        <a href="{{ route('login') }}" 
                           class="inline-block bg-ali-blue text-white font-bold py-3 px-6 rounded-lg hover:bg-ali-blue-dark transition-all duration-200">
                            Ir al Login
                        </a>
                        
                        <p class="text-ali-gray text-sm">
                            ¿Necesitas ayuda? 
                            <span class="text-ali-blue font-semibold">Contacta al administrador</span>
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Image Section -->
        <div class="w-1/2 shadow-2xl">
            <div class="w-full h-screen hidden md:flex items-center justify-center relative overflow-hidden" 
                 style="background-image: url('{{ asset('assets/img/fondologin.svg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                
                <!-- Overlay for better text readability -->
                <div class="absolute inset-0"></div>
                
                <!-- Main content -->
                <div class="text-center text-white z-10 relative">
                    <div class="text-6xl font-bold mb-4 drop-shadow-lg">ALI3000</div>
                    <div class="text-2xl font-light mb-8 drop-shadow-md">Consultores</div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>