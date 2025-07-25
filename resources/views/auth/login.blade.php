<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ALI3000 Consultores</title>
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
                <p class="text-center text-3xl font-bold text-gray-800 mb-2">Bienvenido</p>
                <p class="text-center text-ali-gray mb-8">Ingresa tus credenciales para acceder al sistema</p>
                
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-none">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('status') }}
                    </div>
                @endif
                
                <form class="flex flex-col pt-3 md:pt-8" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="flex flex-col pt-4">
                        <label for="email" class="text-lg font-medium text-gray-700">Correo Electrónico</label>
                        <input type="email" 
                               id="email" 
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="tu@email.com" 
                               class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-ali-blue focus:border-ali-blue transition-all duration-200"
                               required 
                               autofocus>
                    </div>
    
                    <div class="flex flex-col pt-4">
                        <label for="password" class="text-lg font-medium text-gray-700">Contraseña</label>
                        <input type="password" 
                               id="password" 
                               name="password"
                               placeholder="Tu contraseña" 
                               class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-ali-blue focus:border-ali-blue transition-all duration-200"
                               required>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="remember" 
                                   name="remember" 
                                   class="h-4 w-4 text-ali-blue focus:ring-ali-blue border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Recordarme
                            </label>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="text-ali-blue hover:text-ali-orange transition-colors duration-200">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            </div>
                        @endif
                    </div>
    
                    <button type="submit" 
                            class="bg-ali-blue text-white font-bold text-lg hover:bg-ali-blue-dark p-3 mt-8 rounded-lg transition-all duration-200 transform hover:scale-105">
                        Iniciar Sesión
                    </button>
                </form>
                
                <div class="text-center pt-12 pb-12">
                    <p class="text-ali-gray">¿Eres consultor y no tienes cuenta? 
                        <span class="text-ali-blue font-semibold">Contacta al administrador</span>
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="text-ali-gray hover:text-ali-orange transition-colors duration-200 text-sm flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Volver al inicio
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Image Section -->
        <div class="w-1/2 shadow-2xl">
            <div class="w-full h-screen hidden md:flex items-center justify-center relative overflow-hidden" 
                 style="background-image: url('{{ asset('assets/img/fondologin.svg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                
                <!-- Overlay for better text readability -->
                <div class="absolute inset-0 bg-blue-900 bg-opacity-20"></div>
                
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