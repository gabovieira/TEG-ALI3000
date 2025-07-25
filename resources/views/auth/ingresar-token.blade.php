<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - ALI3000 Consultores</title>
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
        .border-ali-blue { border-color: var(--ali-blue); }
        .focus\:border-ali-blue:focus { border-color: var(--ali-blue); }
        .focus\:ring-ali-blue:focus { --tw-ring-color: var(--ali-blue); }
        .hover\:bg-ali-blue-dark:hover { background-color: #3a6d96; }
        
        .gradient-bg {
            background: linear-gradient(135deg, var(--ali-blue) 0%, var(--ali-orange) 100%);
        }
    </style>
</head>
<body class="bg-white font-family-jakarta h-screen">

    <div class="w-full flex flex-wrap">

        <!-- Login Section -->
        <div class="w-full md:w-1/2 flex flex-col">

            <div class="flex justify-center md:justify-start pt-12 md:pl-12 md:-mb-24">
                <div class="flex items-center">
                    <img src="{{ asset('assets/img/logoali3000.png') }}" alt="ALI3000 Logo" class="h-16 w-auto">
                </div>
            </div>

            <div class="flex flex-col justify-center md:justify-start my-auto pt-8 md:pt-0 px-8 md:px-24 lg:px-32">
                <p class="text-center text-3xl font-bold text-gray-800 mb-2">Registro de Consultor</p>
                <p class="text-center text-ali-gray mb-8">Ingresa tu token de invitación para continuar</p>
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                <form class="flex flex-col pt-3 md:pt-8" method="POST" action="{{ route('registro.validar-token') }}">
                    @csrf
                    
                    <div class="flex flex-col pt-4">
                        <label for="token" class="text-lg font-medium text-gray-700">Token de Invitación</label>
                        <input type="text" 
                               id="token" 
                               name="token"
                               value="{{ old('token') }}"
                               placeholder="Ingresa el token que recibiste por email" 
                               class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-ali-blue focus:border-ali-blue transition-all duration-200"
                               required 
                               autofocus>
                        @error('token')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-ali-gray mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            El token te fue enviado por email por el administrador
                        </p>
                    </div>
    
                    <button type="submit" 
                            class="bg-ali-blue text-white font-bold text-lg hover:bg-ali-blue-dark p-3 mt-8 rounded-lg transition-all duration-200 transform hover:scale-105">
                        Continuar Registro
                    </button>
                </form>
                
                <div class="text-center pt-12 pb-12">
                    <p class="text-ali-gray">¿Ya tienes una cuenta? 
                        <a href="{{ route('login') }}" class="text-ali-blue font-semibold hover:text-ali-orange transition-colors duration-200">Iniciar sesión</a>
                    </p>
                </div>
                
                <div class="text-center">
                    <a href="{{ route('home') }}" class="text-ali-gray hover:text-ali-orange transition-colors duration-200 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver al inicio
                    </a>
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
                    <div class="text-lg drop-shadow-md">Completa tu registro para comenzar</div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>