<!-- Loopple Templates: https://www.loopple.com/templates | Copyright Loopple (https://www.loopple.com) | This copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ALI3000 Consultores</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/Loopple/loopple-public-assets@main/motion-tailwind/css/leaflet.css">
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/loopple/loopple.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/landing-styles.css') }}">
</head>

<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container mx-auto px-4">
            <div class="relative flex flex-wrap items-center justify-between w-full group py-7">
            <div>
                <img class="h-20" src="{{ asset('assets/img/logoali3000.png') }}" alt="Logo ALI3000">
            </div>
            <div class="items-center justify-between hidden gap-12 md:flex">
                <a class="text-sm font-normal" style="color:#708090;" href="javascript:void(0)" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Misión</a>
                <a class="text-sm font-normal" style="color:#708090;" href="javascript:void(0)" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Visión</a>
                <a class="text-sm font-normal" style="color:#708090;" href="javascript:void(0)" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Nosotros</a>
                <a class="text-sm font-normal" style="color:#708090;" href="javascript:void(0)" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Contactos</a>
            </div>
            <div class="items-center hidden gap-8 md:flex">
                <a href="{{ route('login') }}" class="flex items-center text-sm font-normal" style="color:#4682B4;">Iniciar Sesión</a>
                <a href="{{ route('registro.token') }}" class="flex items-center px-4 py-2 text-sm font-bold rounded-xl" style="background:#FF6347; color:#fff;">Registrarse</a>
            </div>
            <button onclick="(() => { this.closest('.group').classList.toggle('open')})()" class="flex md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M3 8H21C21.2652 8 21.5196 7.89464 21.7071 7.70711C21.8946 7.51957 22 7.26522 22 7C22 6.73478 21.8946 6.48043 21.7071 6.29289C21.5196 6.10536 21.2652 6 21 6H3C2.73478 6 2.48043 6.10536 2.29289 6.29289C2.10536 6.48043 2 6.73478 2 7C2 7.26522 2.10536 7.51957 2.29289 7.70711C2.48043 7.89464 2.73478 8 3 8ZM21 16H3C2.73478 16 2.48043 16.1054 2.29289 16.2929C2.10536 16.4804 2 16.7348 2 17C2 17.2652 2.10536 17.5196 2.29289 17.7071C2.48043 17.8946 2.73478 18 3 18H21C21.2652 18 21.5196 17.8946 21.7071 17.7071C21.8946 17.5196 22 17.2652 22 17C22 16.7348 21.8946 16.4804 21.7071 16.2929C21.5196 16.1054 21.2652 16 21 16ZM21 11H3C2.73478 11 2.48043 11.1054 2.29289 11.2929C2.10536 11.4804 2 11.7348 2 12C2 12.2652 2.10536 12.5196 2.29289 12.7071C2.48043 12.8946 2.73478 13 3 13H21C21.2652 13 21.5196 12.8946 21.7071 12.7071C21.8946 12.5196 22 12.2652 22 12C22 11.7348 21.8946 11.4804 21.7071 11.2929C21.5196 11.1054 21.2652 11 21 11Z" fill="black"></path>
                </svg>
            </button>
            <div class="absolute flex md:hidden transition-all duration-300 ease-in-out flex-col items-start shadow-main justify-center w-full gap-3 overflow-hidden bg-white max-h-0 group-[.open]:py-4 px-4 rounded-2xl group-[.open]:max-h-64 top-full">
                <a class="text-sm font-normal" style="color:#708090;" href="javascript:void(0)" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Misión</a>
                <a class="text-sm font-normal" style="color:#708090;" href="javascript:void(0)" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Visión</a>
                <a class="text-sm font-normal" style="color:#708090;" href="javascript:void(0)" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Nosotros</a>
                <a class="text-sm font-normal" style="color:#708090;" href="javascript:void(0)" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Contactos</a>
                <a href="{{ route('login') }}" class="flex items-center text-sm font-normal" style="color:#4682B4;">Iniciar Sesión</a>
                <a href="{{ route('registro.token') }}" class="flex items-center px-4 py-2 text-sm font-bold rounded-xl" style="background:#FF6347; color:#fff;">Registrarse</a>
            </div>
        </div>
            <div class="grid w-full grid-cols-1 my-auto mt-12 mb-8 md:grid-cols-2 xl:gap-14 md:gap-5">
                <div class="flex flex-col justify-center col-span-1 text-center lg:text-start">
                    <h1 class="hero-title">Transformamos tu visión en resultados</h1>
                    <p class="text-xl text-slate-600 mb-8">Soluciones tecnológicas a medida para impulsar tu negocio</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#contacto" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-300 text-center">
                            Contáctanos
                        </a>
                        <a href="#nosotros" class="px-8 py-3 border-2 border-blue-600 text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition-colors duration-300 text-center">
                            Conócenos más
                        </a>
                    </div>
                </div>
                <div class="items-center justify-end hidden col-span-1 md:flex">
                    <img class="w-4/5 rounded-lg shadow-xl" src="{{ asset('assets/img/landing.svg') }}" alt="Imagen principal ALI3000">
                </div>
            </div>
        </div>
    </div>
  <!-- Sección: Nosotros -->
  <section id="nosotros" class="landing-section nosotros-section">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto text-center">
                <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold mb-4">Sobre Nosotros</span>
                <h2 class="text-4xl font-bold text-gray-900 mb-6">¿Quiénes somos?</h2>
                <div class="h-1 w-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mx-auto mb-8"></div>
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <p class="text-lg text-gray-700 leading-relaxed mb-6">
                        En <span class="font-semibold text-blue-600">ALI 3000 CONSULTORES, C.A.</span> nos especializamos en la formación y asignación de talento humano de alto nivel en el ámbito tecnológico. Nuestro enfoque se centra en áreas clave como el desarrollo de software, análisis de datos, soporte técnico y consultoría de sistemas.
                    </p>
                    <p class="text-gray-600">
                        A través de nuestro innovador <span class="font-medium text-gray-800">modelo formativo-práctico</span>, capacitamos a profesionales que posteriormente son integrados en empresas líderes bajo esquemas flexibles de contratación por horas o proyectos, impulsando así la transformación digital y el éxito sostenible de organizaciones tanto del sector público como privado.
                    </p>
                </div>
            </div>
        </div>
    </section>

        <!-- Sección: Misión y Visión -->
        <section class="landing-section mision-vision-section">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold mb-4">Nuestro Compromiso</span>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Misión y Visión</h2>
                    <div class="h-1 w-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mx-auto"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">
                    <div class="card-mision-vision">
                        <div class="flex flex-col items-center text-center gap-6">
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-blue-50 rounded-2xl flex items-center justify-center shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Nuestra Misión</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Capacitar, desarrollar y asignar talento humano altamente calificado en el área de tecnología, adaptando sus capacidades a las necesidades del mercado laboral nacional e internacional, mediante un modelo de formación integral basado en la experiencia práctica y la excelencia técnica.
                            </p>
                        </div>
                    </div>
                    
                    <div class="card-mision-vision">
                        <div class="flex flex-col items-center text-center gap-6">
                            <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-blue-100 rounded-2xl flex items-center justify-center shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Nuestra Visión</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Ser reconocidos como la organización de referencia en Latinoamérica en la formación y gestión de profesionales tecnológicos, destacándonos por nuestra calidad humana, capacidad de respuesta y compromiso con la transformación digital de nuestros aliados estratégicos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección: Valores -->
        <section class="landing-section valores-section">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold mb-4">Nuestros Pilares</span>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Valores Corporativos</h2>
                    <div class="h-1 w-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mx-auto"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <div class="valor-card">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 mb-4 mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Excelencia</h3>
                        <p class="text-gray-600">Nos esforzamos por superar expectativas, manteniendo los más altos estándares de calidad en cada proyecto y servicio que ofrecemos.</p>
                    </div>
                    
                    <div class="valor-card">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-50 mb-4 mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Innovación</h3>
                        <p class="text-gray-600">Abrazamos el cambio y la creatividad para desarrollar soluciones tecnológicas vanguardistas que generen valor real.</p>
                    </div>
                    
                    <div class="valor-card">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-indigo-50 mb-4 mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Compromiso</h3>
                        <p class="text-gray-600">Cumplimos con lo prometido, construyendo relaciones a largo plazo basadas en la confianza y la transparencia con nuestros clientes y colaboradores.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección: Contactos -->
        <section id="contacto" class="landing-section contacto-section">
            <div class="container mx-auto px-4">
                <div class="contacto-container">
                    <h2 class="contacto-title">Contáctanos</h2>
                    <div class="h-1 w-20 bg-white rounded-full mb-8 mx-auto"></div>
                    <p class="contacto-text">
                        ¿Tienes dudas o quieres trabajar con nosotros?<br>
                        Nuestro equipo está listo para ayudarte en tu transformación digital.
                    </p>
                    <div class="flex flex-wrap justify-center gap-4 mt-8">
                        <a href="mailto:info@ali3000.com" class="btn-contacto btn-email">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            info@ali3000.com
                        </a>
                        <a href="#" class="btn-contacto btn-form">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            +1 234 567 890
                        </a>
                    </div>
                </div>
            </div>
        </section>
        </div>
    </div>
    <div class="w-full">
        <div class="container flex flex-col items-center gap-8 mx-auto my-32">
            <p class="text-base font-medium leading-7 text-center text-dark-grey-600">Compañías con las que trabajamos</p>
            <div class="flex flex-wrap items-center justify-center w-full gap-6 lg:gap-0 lg:flex-nowrap lg:justify-center">
                <!-- Logo Universitas completo (icono + texto vectorizado) -->
                <svg viewBox="0 0 1081.05 217.36" xmlns="http://www.w3.org/2000/svg" style="height:80px; max-width:320px; object-fit:contain;">
                  <g>
                    <path d="M138.27,24.29c-.88,4.82.47,9.35,1.6,13.99,1.2,4.96,2.34,9.94,3.87,14.82.9,2.87,1.78,5.74,2.65,8.62,3.39,11.2,6.61,22.45,9.69,33.74,8.62,31.55,5.97,50.03,5.14,54.25.36.71.72,1.41,1.08,2.08,2.35,4.38,5.78,8.22,9.52,11.47,10.87,9.45,28.81,14.23,41.44,5.37,14.26-10.01,15.24-31.29,15.58-47.4-5.66-1.85-11.13-4.38-16.29-7.68-14.95-9.57-26.53-23.82-37.19-37.53-10.85-13.96-20.88-29.08-26.83-45.6-3.74-10.39-2.86-18.25-2.68-19.53-3.82,3.58-6.62,8.1-7.59,13.42" fill="#014f7e"/>
                    <path d="M0,25.47c4.48,4.56,8.53,9.61,12.43,14.67,13.58,17.58,25,36.97,33.37,57.56,10.29,25.3,11.46,54.87,26.64,77.99,4.07,6.2,8.89,11.88,14.44,16.8,3.08,2.73,6.4,5.18,9.88,7.36,13.15,8.26,28.48,12.96,43.86,14.67,19.9,2.21,41.72-.15,58.79-11.34,14.92-9.77,26.67-25.12,31.78-42.24,1.82-6.11,2.88-12.53,3.2-18.87h0c.42-9.2-.81-18.69-2.64-27.71-.3-1.49-.62-2.98-1.05-4.44-.16-.53-.39-1.99-.92-2.33-1.06-.69-.87,3.74-.87,4.26,0,2.39,0,4.78-.04,7.18-.32,16.32-.71,39.11-15.63,49.58-12.63,8.87-30.57,4.09-41.44-5.37-3.74-3.25-7.17-7.09-9.52-11.47-16.74-31.19-31.52-103.33-58.47-128.95-10.7-10.18-25-13.66-39.57-13.65C32.74,9.17,0,25.47,0,25.47" fill="#014f7e"/>
                    <path d="M145.89,10.66s-1.45,8.37,2.65,19.73c5.95,16.53,15.98,31.65,26.83,45.6,10.66,13.71,22.24,27.96,37.19,37.53,16.92,10.83,37.11,13.4,56.77,10.72,6.89-.94,13.59-3.08,19.88-6.03,5.82-2.73,11.31-6.15,16.43-10.02,4.32-3.26,8.41-6.84,12.23-10.67,2.54-2.55-40.23,9.62-85.91-57.58C210.51,8.37,190.21,0,174.73,0c-17.48,0-28.84,10.66-28.84,10.66" fill="#014f7e"/>
                  </g>
                  <g>
                    <path d="M342.53,89.89l3.74-6.34c3.69,2.65,6.64,3.69,10.13,3.69,3.69,0,5.94-1.45,5.94-3.89s-1.6-3.64-6.99-5.14c-8.24-2.25-11.38-5.29-11.38-10.98,0-6.54,5.39-10.93,13.23-10.93,4.59,0,8.39,1.05,12.18,3.44l-3.69,6.29c-3.09-1.8-5.59-2.54-8.38-2.54-3.39,0-5.44,1.25-5.44,3.34s1.45,3.04,6.99,4.74c7.84,2.4,11.38,5.94,11.38,11.43,0,6.84-5.54,11.43-13.83,11.43-5.09,0-10.03-1.65-13.88-4.54Z" fill="#014f7e"/>
                    <path d="M415.3,81.75h-21.61c.95,3.74,4.14,6.14,8.58,6.14,2.89,0,5.34-.9,7.19-2.65l4.39,4.84c-3.39,3.04-6.84,4.34-11.63,4.34-9.38,0-15.92-6.09-15.92-14.87s6.54-15.27,15.22-15.27c8.14,0,13.98,5.84,13.98,14.03,0,1.85-.05,2.84-.2,3.44ZM408.37,76.11c-.15-3.14-2.94-5.29-6.89-5.29s-6.59,2-7.64,5.29h14.52Z" fill="#014f7e"/>
                    <path d="M455.99,64.78h6.29v25.46c0,8.33-5.94,13.48-15.42,13.48-5.29,0-9.83-1.35-13.58-4.04l3.64-5.69c3.09,2.15,6.19,3.19,9.73,3.19,5.24,0,8.19-2.7,8.19-7.59v-1.1c-1.95,2.7-5.09,4.14-8.88,4.14-7.89,0-14.02-6.39-14.02-14.52s5.99-13.83,14.02-13.83c3.94,0,6.84,1.2,9.43,3.94l.6-3.44ZM454.94,78.41c0-4.29-3.29-7.49-7.74-7.49s-7.79,3.19-7.79,7.49,3.29,7.54,7.79,7.54,7.74-3.19,7.74-7.54Z" fill="#014f7e"/>
                    <path d="M501.91,64.78h7.49v29.15h-6.29l-.6-3.69c-2.45,2.79-5.19,4.19-8.83,4.19-7.44,0-11.98-4.74-11.98-12.48v-17.17h7.44v15.37c0,4.69,2.15,7.09,6.14,7.09,4.44,0,6.64-2.99,6.64-8.48v-13.98Z" fill="#014f7e"/>
                    <path d="M546.39,64.68v7.54c-.95-.15-1.9-.25-2.75-.25-4.74,0-7.14,2.84-7.14,7.19v14.77h-7.44v-29.15h6.24l.6,3.74c2.2-2.45,5.14-3.99,8.68-3.99.6,0,1.2.05,1.8.15Z" fill="#014f7e"/>
                    <path d="M560.72,79.36c0-8.53,6.74-15.07,15.27-15.07s15.32,6.59,15.32,15.07-6.74,15.07-15.32,15.07-15.27-6.59-15.27-15.07ZM583.72,79.36c0-4.49-3.34-7.79-7.74-7.79s-7.74,3.29-7.74,7.79,3.34,7.79,7.74,7.79,7.74-3.34,7.74-7.79Z" fill="#014f7e"/>
                    <path d="M607.99,91.29l3.19-5.84c2.5,1.45,5.79,2.4,8.38,2.4,3.24,0,4.94-.85,4.94-2.55,0-1.5-1.4-2.4-5.04-3.04-7.24-1.35-10.38-4.14-10.38-9.18s4.59-8.79,11.53-8.79c4.09,0,7.09.7,10.53,2.55l-3.29,5.99c-2.79-1.45-4.64-1.95-7.24-1.9-2.6.05-4.09.8-4.09,2.15,0,1.5,1.25,2.25,5.14,3.04,7.09,1.5,10.28,4.34,10.28,9.18,0,5.64-4.79,9.13-12.38,9.13-4.29,0-8.53-1.15-11.58-3.14Z" fill="#014f7e"/>
                  </g>
                  <g>
                    <path d="M341.98,179v-56.74h19.25v56.24c0,12,7.62,19.25,20.12,19.25s20-7.25,20-19.25v-56.24h19.25v56.74c0,23.12-15.12,37.62-39.24,37.62s-39.37-14.5-39.37-37.62Z" fill="#014f7e"/>
                    <path d="M508.57,172.25v42.99h-18.62v-38.49c0-11.75-5.37-17.75-15.75-17.75-11.25,0-17.25,7.5-17.25,21.25v34.99h-18.62v-72.98h15.75l1.37,8.5c6.25-6.62,14.12-9.75,22.74-9.75,18.87,0,30.37,11.87,30.37,31.24Z" fill="#014f7e"/>
                    <path d="M524.31,121.38c0-6,4.87-10.87,10.87-10.87s11,4.87,11,10.87-4.87,10.75-11,10.75-10.87-4.87-10.87-10.75ZM525.94,215.24v-72.98h18.62v72.98h-18.62Z" fill="#014f7e"/>
                    <path d="M610.04,142.25h21.12l-39.74,75.11-39.74-75.11h21.12l18.62,38.74,18.62-38.74Z" fill="#014f7e"/>
                    <path d="M703.77,184.74h-54.11c2.38,9.37,10.37,15.37,21.5,15.37,7.25,0,13.37-2.25,18-6.62l11,12.12c-8.5,7.62-17.12,10.87-29.12,10.87-23.49,0-39.87-15.25-39.87-37.24s16.37-38.24,38.12-38.24c20.37,0,34.99,14.62,34.99,35.12,0,4.62-.12,7.12-.5,8.62ZM686.4,170.62c-.38-7.87-7.37-13.25-17.25-13.25s-16.5,5-19.12,13.25h36.37Z" fill="#014f7e"/>
                    <path d="M761.75,142v18.87c-2.38-.38-4.75-.62-6.87-.62-11.87,0-17.87,7.12-17.87,18v36.99h-18.62v-72.98h15.62l1.5,9.37c5.5-6.12,12.87-10,21.75-10,1.5,0,3,.12,4.5.38Z" fill="#014f7e"/>
                    <path d="M768.63,208.61l8-14.62c6.25,3.62,14.5,6,20.99,6,8.12,0,12.37-2.12,12.37-6.37,0-3.75-3.5-6-12.62-7.62-18.12-3.37-25.99-10.37-25.99-22.99s11.5-21.99,28.87-21.99c10.25,0,17.75,1.75,26.37,6.37l-8.25,15c-7-3.62-11.62-4.87-18.12-4.75-6.5.12-10.25,2-10.25,5.37,0,3.75,3.12,5.62,12.87,7.62,17.75,3.75,25.74,10.87,25.74,22.99,0,14.12-12,22.87-30.99,22.87-10.75,0-21.37-2.88-28.99-7.87Z" fill="#014f7e"/>
                    <path d="M841.23,121.38c0-6,4.87-10.87,10.87-10.87s11,4.87,11,10.87-4.87,10.75-11,10.75-10.87-4.87-10.87-10.75ZM842.86,215.24v-72.98h18.62v72.98h-18.62Z" fill="#014f7e"/>
                    <path d="M925.21,210.99c-4.5,3.62-10.75,5.5-18.37,5.5-15.37,0-24.49-9.25-24.49-24.37l.12-32.74h-10.5v-17.12h10.5v-11.87l18.75-3.37v15.25h19.37v17.12h-19.5v31.62c0,5.25,2.88,8.37,7.87,8.37,3.62,0,5.62-.88,8-2.75l8.25,14.37Z" fill="#014f7e"/>
                    <path d="M990.57,142.25h15.87v72.98h-15.87l-1.38-8.62c-5.5,5.75-13.37,9.87-22.74,9.87-19.87,0-36.12-16.5-36.12-37.74s16.25-37.74,36.12-37.74c9.37,0,17.25,4.12,22.74,9.87l1.38-8.62ZM987.95,178.75c0-11.25-8.37-19.5-19.37-19.5s-19.37,8.25-19.37,19.5,8.37,19.5,19.37,19.5,19.37-8.37,19.37-19.5Z" fill="#014f7e"/>
                    <path d="M1021.06,208.61l8-14.62c6.25,3.62,14.5,6,20.99,6,8.12,0,12.37-2.12,12.37-6.37,0-3.75-3.5-6-12.62-7.62-18.12-3.37-25.99-10.37-25.99-22.99s11.5-21.99,28.87-21.99c10.25,0,17.75,1.75,26.37,6.37l-8.25,15c-7-3.62-11.62-4.87-18.12-4.75-6.5.12-10.25,2-10.25,5.37,0,3.75,3.12,5.62,12.87,7.62,17.75,3.75,25.74,10.87,25.74,22.99,0,14.12-12,22.87-30.99,22.87-10.75,0-21.37-2.88-28.99-7.87Z" fill="#014f7e"/>
                  </g>
                </svg>
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="container flex flex-col mx-auto">
            <div class="flex flex-col items-center w-full my-20">
                <span class="mb-8 flex flex-col items-center">
                    <img src="{{ asset('assets/img/logoali3000.png') }}" alt="Logo ALI3000" class="h-20 mx-auto">
                    <span class="ml-2 text-sm font-bold tracking-widest uppercase" style="color:#000000;">ALI3000 Consultores</span>
                </span>
                <div class="flex flex-col items-center gap-6 mb-8">
                    <div class="flex flex-wrap items-center justify-center gap-5 lg:gap-12 gap-y-3 lg:flex-nowrap">
                        <a href="#" style="color:#708090;" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Inicio</a>
                        <a href="#" style="color:#708090;" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Servicios</a>
                        <a href="#" style="color:#708090;" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Equipo</a>
                        <a href="#" style="color:#708090;" onmouseover="this.style.color='#FF6347'" onmouseout="this.style.color='#708090'">Contacto</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <p class="ml-2 text-sm font-bold tracking-widest uppercase" style="color:#4682B4;">© 2025 ALI3000 Consultores, C.A. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/gh/Loopple/loopple-public-assets@main/motion-tailwind/scripts/countto.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/Loopple/loopple-public-assets@main/motion-tailwind/scripts/plugins/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/Loopple/loopple-public-assets@main/motion-tailwind/scripts/maps.js"></script>
</body>