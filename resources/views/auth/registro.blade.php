<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Registro - ALI3000 Consultores</title>
    <meta name="description" content="Sistema de gestión de consultores ALI3000">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

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
    </style>
</head>
<body class="bg-white font-family-jakarta h-screen">

    <div class="w-full flex flex-wrap">

        <!-- Registration Section -->
        <div class="w-full md:w-1/2 flex flex-col">

            <div class="flex justify-center md:justify-start pt-12 md:pl-12 md:-mb-24">
                <div class="flex items-center">
                    <img src="{{ asset('assets/img/logoali3000.png') }}" alt="ALI3000 Logo" class="h-16 w-auto" onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCAxMDAgNTAiPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZm9udC1zaXplPSIxOCIgZm9udC13ZWlnaHQ9IjYwMCIgZmlsbD0iIzQ2ODJCNCAiPkFMSTMwMDA8L3RleHQ+PC9zdmc+'; this.classList.add('border', 'border-gray-200', 'rounded');">
                </div>
            </div>

            <div class="flex flex-col justify-center md:justify-start my-auto pt-8 md:pt-0 px-8 md:px-24 lg:px-32">
                <p class="text-center text-3xl font-bold text-gray-800 mb-2">Completar Registro</p>
                <p class="text-center text-ali-gray mb-8">Completa tu información para acceder al sistema</p>
                
                <!-- Token Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Invitación válida hasta:</p>
                            <p class="text-sm text-blue-600">
                                @if(isset($tokenRegistro) && $tokenRegistro->fecha_expiracion)
                                    {{ $tokenRegistro->fecha_expiracion->format('d/m/Y H:i') }}
                                @else
                                    Fecha no disponible
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-none">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                <!-- Datos Pre-llenados -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Datos registrados por el administrador:</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-ali-gray">Primer Nombre</label>
                            <p class="font-medium">
                                @if(isset($usuario) && $usuario->primer_nombre)
                                    {{ $usuario->primer_nombre }}
                                @else
                                    No disponible
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-ali-gray">Primer Apellido</label>
                            <p class="font-medium">
                                @if(isset($usuario) && $usuario->primer_apellido)
                                    {{ $usuario->primer_apellido }}
                                @else
                                    No disponible
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-ali-gray">Email</label>
                            <p class="font-medium">
                                @if(isset($usuario) && $usuario->email)
                                    {{ $usuario->email }}
                                @else
                                    No disponible
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <form class="flex flex-col pt-3" method="POST" action="{{ route('registro.procesar', $token ?? '') }}">
                    @csrf
                    
                    <div class="flex flex-col pt-4">
                        <label for="telefono" class="text-lg font-medium text-gray-700">Teléfono</label>
                        <input type="tel" 
                               id="telefono" 
                               name="telefono"
                               value="{{ old('telefono') }}"
                               placeholder="+58 412 123 4567" 
                               class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-ali-blue focus:border-ali-blue transition-all duration-200">
                    </div>
    
                    <div class="flex flex-col pt-4">
                        <label for="password" class="text-lg font-medium text-gray-700">Contraseña *</label>
                        <input type="password" 
                               id="password" 
                               name="password"
                               placeholder="Mínimo 8 caracteres" 
                               class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-ali-blue focus:border-ali-blue transition-all duration-200"
                               required>
                    </div>
                    
                    <div class="flex flex-col pt-4">
                        <label for="password_confirmation" class="text-lg font-medium text-gray-700">Confirmar Contraseña *</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation"
                               placeholder="Repite la contraseña" 
                               class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-ali-blue focus:border-ali-blue transition-all duration-200"
                               required>
                    </div>
                    
                    <div class="flex items-center pt-4">
                        <input type="checkbox" 
                               id="terminos" 
                               name="terminos" 
                               required
                               class="h-4 w-4 text-ali-blue focus:ring-ali-blue border-gray-300 rounded">
                        <label for="terminos" class="ml-2 block text-sm text-gray-700">
                            Acepto los <a href="#" class="text-ali-blue hover:text-ali-orange">términos y condiciones</a> del sistema
                        </label>
                    </div>
    
                    <button type="submit" 
                            class="bg-ali-blue text-white font-bold text-lg hover:bg-ali-blue-dark p-3 mt-8 rounded-lg transition-all duration-200 transform hover:scale-105">
                        Completar Registro
                    </button>
                </form>
                
                <div class="text-center pt-12 pb-12">
                    <p class="text-ali-gray">¿Problemas con el registro? 
                        <span class="text-ali-blue font-semibold">Contacta al administrador</span>
                    </p>
                </div>
            </div>

        </div>

        <!-- Image Section -->
        <div class="w-1/2 shadow-2xl">
            <div class="w-full h-screen hidden md:flex items-center justify-center relative overflow-hidden" 
                 style="background-image: url('{{ asset('assets/img/fondologin.svg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
                 onerror="this.style.backgroundColor = '#4682B4';">
                
                <!-- Overlay for better text readability -->
                <div class="absolute inset-0 bg-blue-900 bg-opacity-20"></div>
                
                <!-- Main content -->
                <div class="text-center text-white z-10 relative">
                    <div class="text-6xl font-bold mb-4 drop-shadow-lg">ALI3000</div>
                    <div class="text-2xl font-light mb-8 drop-shadow-md">Consultores</div>
                    <div class="text-lg drop-shadow-md">Completa tu registro para comenzar</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Formatear teléfono venezolano
        document.getElementById('telefono').addEventListener('input', function(e) {
            let value = this.value.replace(/[^0-9+]/g, '');
            
            // Si no empieza con +58, agregarlo
            if (value && !value.startsWith('+58')) {
                if (value.startsWith('58')) {
                    value = '+' + value;
                } else if (value.startsWith('0')) {
                    value = '+58' + value.substring(1);
                } else {
                    value = '+58' + value;
                }
            }
            
            this.value = value;
        });

        // Validación de confirmación de contraseña
        document.getElementById('password_confirmation').addEventListener('input', function(e) {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            
            if (password !== confirmation) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // Verificar si las imágenes se cargan correctamente
        window.addEventListener('load', function() {
            console.log('Página cargada completamente');
            
            // Verificar si las imágenes se cargaron correctamente
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                if (!img.complete || img.naturalHeight === 0) {
                    console.error('Error al cargar la imagen:', img.src);
                } else {
                    console.log('Imagen cargada correctamente:', img.src);
                }
            });
        });
    </script>

</body>
</html>