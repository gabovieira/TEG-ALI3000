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
</head>

<body>
    <div class="container flex flex-col mx-auto">
        <div class="relative flex flex-wrap items-center justify-between w-full bg-white group py-7 shrink-0">
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
                <button class="flex items-center text-sm font-normal" style="color:#4682B4;">Iniciar Sesión</button>
                <button class="flex items-center px-4 py-2 text-sm font-bold rounded-xl" style="background:#FF6347; color:#fff;">Registrarse</button>
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
                <button class="flex items-center text-sm font-normal" style="color:#4682B4;">Iniciar Sesión</button>
                <button class="flex items-center px-4 py-2 text-sm font-bold rounded-xl" style="background:#FF6347; color:#fff;">Registrarse</button>
            </div>
        </div>
        <div class="grid w-full grid-cols-1 my-auto mt-12 mb-8 md:grid-cols-2 xl:gap-14 md:gap-5">
            <div class="flex flex-col justify-center col-span-1 text-center lg:text-start">
                <div class="flex items-center justify-center mb-4 lg:justify-normal">
                  
                </div>
                <h1 class="mb-8 text-4xl font-extrabold leading-tight lg:text-6xl" style="color:#000000;">Transformamos tu visión en resultados</h1>

                <div class="flex flex-col items-center gap-4 lg:flex-row">
                    <button class="flex items-center py-4 text-sm font-bold px-7 rounded-xl" style="background:#4682B4; color:#fff;">Comienza ahora</button>
                    <button class="flex items-center py-4 text-sm font-medium px-7 rounded-2xl" style="color:#FF6347; border:2px solid #FF6347;">Solicita una llamada</button>
                </div>
            </div>
            <div class="items-center justify-end hidden col-span-1 md:flex">
                <img class="w-4/5 rounded-md" src="{{ asset('assets/img/landing.svg') }}" alt="Imagen principal ALI3000">
            </div>
        </div>

        <!-- Sección: Nosotros -->
        <section class="w-full bg-white py-16">
            <div class="container mx-auto max-w-4xl flex flex-col items-center text-center gap-6">
                <h2 class="text-3xl font-extrabold" style="color:#000000;">¿Quiénes somos?</h2>
                <p class="text-base font-medium leading-7" style="color:#708090;">
                    ALI 3000 CONSULTORES, C.A. es una empresa venezolana dedicada a la formación y asignación de talento humano especializado en áreas tecnológicas como desarrollo de software, análisis de datos, soporte técnico y consultoría de sistemas. A través de nuestro modelo formativo-práctico, capacitamos profesionales que luego son ubicados en empresas clientes bajo esquemas de contratación por horas o proyectos, contribuyendo al éxito de organizaciones públicas y privadas.
                </p>
            </div>
        </section>

        <!-- Sección: Misión y Visión -->
        <section class="w-full bg-gray-50 py-16">
            <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 max-w-4xl">
                <div class="flex flex-col items-center text-center gap-4">
                    <h2 class="text-2xl font-bold" style="color:#4682B4;">Misión</h2>
                    <p class="text-base font-medium leading-7" style="color:#708090;">
                        Capacitar, desarrollar y asignar talento humano altamente calificado en el área de tecnología, adaptando sus capacidades a las necesidades del mercado laboral nacional e internacional, mediante un modelo de formación integral basado en la experiencia práctica y la excelencia técnica.
                    </p>
                </div>
                <div class="flex flex-col items-center text-center gap-4">
                    <h2 class="text-2xl font-bold" style="color:#4682B4;">Visión</h2>
                    <p class="text-base font-medium leading-7" style="color:#708090;">
                        Ser reconocidos como la organización de referencia en Latinoamérica en la formación y gestión de profesionales tecnológicos, destacándonos por nuestra calidad humana, capacidad de respuesta y compromiso con la transformación digital de nuestros aliados estratégicos.
                    </p>
                </div>
            </div>
        </section>

        <!-- Sección: Contactos -->
        <section class="w-full bg-white py-16">
            <div class="container mx-auto max-w-2xl flex flex-col items-center text-center gap-6">
                <h2 class="text-2xl font-bold" style="color:#4682B4;">Contactos</h2>
                <p class="text-base font-medium leading-7" style="color:#708090;">
                    ¿Tienes dudas o quieres trabajar con nosotros?<br>
                    Escríbenos a <a href="mailto:info@ali3000.com" class="underline text-blue-600">info@ali3000.com</a> o utiliza nuestro formulario de contacto.
                </p>
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
    <div class="container mx-auto">
        <div class="flex flex-col items-center justify-center h-full">

            <div class="flex flex-col items-center justify-center mt-12">
                <h2 class="mb-4 text-3xl font-extrabold leading-tight text-center lg:text-4xl" style="color:#000000;">Impulsa tu productividad con ALI3000</h2>
                <p class="text-lg text-center lg:w-7/12" style="color:#708090;">Descubre cómo nuestras soluciones pueden transformar tu empresa y optimizar tus procesos para alcanzar el éxito.</p>
            </div>
            <div class="grid grid-cols-1 gap-10 mt-20 lg:grid-cols-3 md:grid-cols-2">
                <div class="flex flex-col items-center col-span-1 gap-6 px-10 py-5">
                    <div>
                        <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-purple-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                <path d="M22.8233 8C22.8233 7.28 22.4533 6.65 21.8833 6.3L12.8333 1L3.78331 6.3C3.21331 6.65 2.83331 7.28 2.83331 8V18C2.83331 19.1 3.73331 20 4.83331 20H20.8333C21.9333 20 22.8333 19.1 22.8333 18L22.8233 8ZM20.8233 8V8.01L12.8333 13L4.83331 8L12.8333 3.32L20.8233 8ZM4.83331 18V10.34L12.8333 15.36L20.8233 10.37L20.8333 18H4.83331Z" fill="white"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 px-2 text-center">
                        <h4 class="text-2xl font-extrabold" style="color:#4682B4;">Comunicación de equipo eficiente</h4>
                        <p class="font-medium" style="color:#708090;">Mejora la colaboración y mantén a tu equipo alineado con nuestras herramientas de comunicación.</p>
                    </div>
                </div>
                <div class="flex flex-col items-center col-span-1 gap-6 px-10 py-5 bg-white shadow-main rounded-3xl">
                    <div>
                        <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-purple-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                <path d="M3.28996 14.78L3.19996 14.69C2.80996 14.3 2.80996 13.67 3.19996 13.28L9.28996 7.18C9.67996 6.79 10.31 6.79 10.7 7.18L13.99 10.47L20.38 3.29C20.76 2.86 21.43 2.85 21.83 3.25C22.2 3.63 22.22 4.23 21.87 4.62L14.7 12.69C14.32 13.12 13.66 13.14 13.25 12.73L9.99996 9.48L4.69996 14.78C4.31996 15.17 3.67996 15.17 3.28996 14.78ZM4.69996 20.78L9.99996 15.48L13.25 18.73C13.66 19.14 14.32 19.12 14.7 18.69L21.87 10.62C22.22 10.23 22.2 9.63 21.83 9.25C21.43 8.85 20.76 8.86 20.38 9.29L13.99 16.47L10.7 13.18C10.31 12.79 9.67996 12.79 9.28996 13.18L3.19996 19.28C2.80996 19.67 2.80996 20.3 3.19996 20.69L3.28996 20.78C3.67996 21.17 4.31996 21.17 4.69996 20.78Z" fill="white"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 px-2 text-center">
                        <h4 class="text-2xl font-extrabold" style="color:#4682B4;">Analítica y métricas</h4>
                        <p class="font-medium" style="color:#708090;">Obtén información valiosa y toma decisiones informadas con nuestros paneles de análisis.</p>
                    </div>
                </div>
                <div class="flex flex-col items-center col-span-1 gap-6 px-10 py-5">
                    <div>
                        <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-purple-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                <path d="M15.8766 12.71C16.857 11.9387 17.5726 10.8809 17.9239 9.68394C18.2751 8.48697 18.2445 7.21027 17.8364 6.03147C17.4283 4.85267 16.6629 3.83039 15.6467 3.10686C14.6305 2.38332 13.4141 1.99451 12.1666 1.99451C10.9192 1.99451 9.70274 2.38332 8.68655 3.10686C7.67037 3.83039 6.90497 4.85267 6.49684 6.03147C6.0887 7.21027 6.05814 8.48697 6.40938 9.68394C6.76063 10.8809 7.47623 11.9387 8.45662 12.71C6.7767 13.383 5.31091 14.4994 4.21552 15.9399C3.|12012 17.3805 2.43619 19.0913 2.23662 20.89C2.22218 21.0213 2.23374 21.1542 2.27065 21.2811C2.30756 21.4079 2.36909 21.5263 2.45173 21.6293C2.61864 21.8375 2.86141 21.9708 3.12662 22C3.39184 22.0292 3.65778 21.9518 3.86595 21.7849C4.07411 21.618 4.20745 21.3752 4.23662 21.11C4.45621 19.1552 5.38831 17.3498 6.85484 16.0388C8.32137 14.7278 10.2195 14.003 12.1866 14.003C14.1537 14.003 16.0519 14.7278 17.5184 16.0388C18.9849 17.3498 19.917 19.1552 20.1366 21.11C20.1638 21.3557 20.2811 21.5827 20.4657 21.747C20.6504 21.9114 20.8894 22.0015 21.1366 22H21.2466C21.5088 21.9698 21.7483 21.8373 21.9132 21.6313C22.078 21.4252 22.1547 21.1624 22.1266 20.9C21.9261 19.0962 21.2385 17.381 20.1375 15.9382C19.0364 14.4954 17.5635 13.3795 15.8766 12.71ZM12.1666 12C11.3755 12 10.6021 11.7654 9.94434 11.3259C9.28655 10.8864 8.77385 10.2616 8.4711 9.53074C8.16835 8.79983 8.08914 7.99557 8.24348 7.21964C8.39782 6.44372 8.77879 5.73099 9.3382 5.17158C9.89761 4.61217 10.6103 4.2312 11.3863 4.07686C12.1622 3.92252 12.9665 4.00173 13.6974 4.30448C14.4283 4.60724 15.053 5.11993 15.4925 5.77772C15.932 6.43552 16.1666 7.20888 16.1666 8C16.1666 9.06087 15.7452 10.0783 14.9951 10.8284C14.2449 11.5786 13.2275 12 12.1666 12Z" fill="white"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 px-2 text-center">
                        <h4 class="text-2xl font-extrabold" style="color:#4682B4;">Soluciones inmediatas</h4>
                        <p class="font-medium" style="color:#708090;">Responde rápidamente a tus clientes y resuelve problemas de forma eficiente.</p>
                    </div>
                </div>
                <div class="flex flex-col items-center col-span-1 gap-6 px-10 py-5">
                    <div>
                        <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-purple-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                <path d="M7.83331 4C7.83331 2.89 6.94331 2 5.83331 2C4.72331 2 3.83331 2.89 3.83331 4C3.83331 5.11 4.72331 6 5.83331 6C6.94331 6 7.83331 5.11 7.83331 4ZM11.0233 4.5C10.6133 4.5 10.2633 4.75 10.1033 5.13C9.66331 6.23 8.59331 7 7.33331 7H4.33331C3.50331 7 2.83331 7.67 2.83331 8.5V11H8.83331V8.74C10.2633 8.29 11.4133 7.21 11.9533 5.83C12.2133 5.19 11.7133 4.5 11.0233 4.5ZM19.8333 17C20.9433 17 21.8333 16.11 21.8333 15C21.8333 13.89 20.9433 13 19.8333 13C18.7233 13 17.8333 13.89 17.8333 15C17.8333 16.11 18.7233 17 19.8333 17ZM21.3333 18H18.3333C17.0733 18 16.0033 17.23 15.5633 16.13C15.4133 15.75 15.0533 15.5 14.6433 15.5C13.9533 15.5 13.4533 16.19 13.7033 16.83C14.2533 18.21 15.3933 19.29 16.8233 19.74V22H22.8233V19.5C22.8333 18.67 22.1633 18 21.3333 18ZM18.0833 11.09C18.0833 11.09 18.0833 11.08 18.0933 11.09C17.0333 11.36 16.1933 12.2 15.9233 13.26V13.25C15.8133 13.68 15.4133 14 14.9433 14C14.3933 14 13.9433 13.55 13.9433 13C13.9433 12.95 13.9633 12.86 13.9633 12.86C14.3933 11.01 15.8533 9.55 17.7133 9.13C17.7533 9.13 17.7933 9.12 17.8333 9.12C18.3833 9.12 18.8333 9.57 18.8333 10.12C18.8333 10.58 18.5133 10.98 18.0833 11.09ZM18.8333 6.06C18.8333 6.57 18.4633 6.98 17.9733 7.05C14.7833 7.44 12.2733 9.96 11.8833 13.15C11.8133 13.63 11.3933 14 10.8933 14C10.3433 14 9.89331 13.55 9.89331 13C9.89331 12.98 9.89331 12.96 9.89331 12.94C9.89331 12.93 9.89331 12.92 9.89331 12.91C10.3933 8.79 13.6833 5.53 17.8133 5.06H17.8233C18.3833 5.06 18.8333 5.51 18.8333 6.06Z" fill="white"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 px-2 text-center">
                        <h4 class="text-2xl font-extrabold" style="color:#4682B4;">Conexión con clientes</h4>
                        <p class="font-medium" style="color:#708090;">Fortalece la relación con tus clientes y mejora la experiencia de atención.</p>
                    </div>
                </div>
                <div class="flex flex-col items-center col-span-1 gap-6 px-10 py-5">
                    <div>
                        <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-purple-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                <path d="M10.5 13H3.49998C3.23477 13 2.98041 13.1054 2.79288 13.2929C2.60534 13.4804 2.49998 13.7348 2.49998 14V21C2.49998 21.2652 2.60534 21.5196 2.79288 21.7071C2.98041 21.8946 3.23477 22 3.49998 22H10.5C10.7652 22 11.0196 21.8946 11.2071 21.7071C11.3946 21.5196 11.5 21.2652 11.5 21V14C11.5 13.7348 11.3946 13.4804 11.2071 13.2929C11.0196 13.1054 10.7652 13 10.5 13ZM9.49998 20H4.49998V15H9.49998V20ZM21.5 2H14.5C14.2348 2 13.9804 2.10536 13.7929 2.29289C13.6053 2.48043 13.5 2.73478 13.5 3V10C13.5 10.2652 13.6053 10.5196 13.7929 10.7071C13.9804 10.8946 14.2348 11 14.5 11H21.5C21.7652 11 22.0196 10.8946 22.2071 10.7071C22.3946 10.5196 22.5 10.2652 22.5 10V3C22.5 2.73478 22.3946 2.48043 22.2071 2.29289C22.0196 2.10536 21.7652 2 21.5 2ZM20.5 9H15.5V4H20.5V9ZM21.5 13H14.5C14.2348 13 13.9804 13.1054 13.7929 13.2929C13.6053 13.4804 13.5 13.7348 13.5 14V21C13.5 21.2652 13.6053 21.5196 13.7929 21.7071C13.9804 21.8946 14.2348 22 14.5 22H21.5C21.7652 22 22.0196 21.8946 22.2071 21.7071C22.3946 21.5196 22.5 21.2652 22.5 21V14C22.5 13.7348 22.3946 13.4804 22.2071 13.2929C22.0196 13.1054 21.7652 13 21.5 13ZM20.5 20H15.5V15H20.5V20ZM10.5 2H3.49998C3.23477 2 2.98041 2.10536 2.79288 2.29289C2.60534 2.48043 2.49998 2.73478 2.49998 3V10C2.49998 10.2652 2.60534 10.5196 2.79288 10.7071C2.98041 10.8946 3.23477 11 3.49998 11H10.5C10.7652 11 11.0196 10.8946 11.2071 10.7071C11.3946 10.5196 11.5 10.2652 11.5 10V3C11.5 2.73478 11.3946 2.48043 11.2071 2.29289C11.0196 2.10536 10.7652 2 10.5 2ZM9.49998 9H4.49998V4H9.49998V9Z" fill="white"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 px-2 text-center">
                        <h4 class="text-2xl font-extrabold" style="color:#4682B4;">Integración web sencilla</h4>
                        <p class="font-medium" style="color:#708090;">Integra fácilmente nuestras soluciones en tu web y mejora la comunicación.</p>
                    </div>
                </div>
                <div class="flex flex-col items-center col-span-1 gap-6 px-10 py-5">
                    <div>
                        <div class="flex items-center justify-center w-16 h-16 rounded-2xl bg-purple-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                <path d="M10.1666 14C9.90141 14 9.64706 14.1054 9.45952 14.2929C9.27198 14.4804 9.16663 14.7348 9.16663 15V21C9.16663 21.2652 9.27198 21.5196 9.45952 21.7071C9.64706 21.8946 9.90141 22 10.1666 22C10.4318 22 10.6862 21.8946 10.8737 21.7071C11.0613 21.5196 11.1666 21.2652 11.1666 21V15C11.1666 14.7348 11.0613 14.4804 10.8737 14.2929C10.6862 14.1054 10.4318 14 10.1666 14ZM5.16663 18C4.90141 18 4.64706 18.1054 4.45952 18.2929C4.27198 18.4804 4.16663 18.7348 4.16663 19V21C4.16663 21.2652 4.27198 21.5196 4.45952 21.7071C4.64706 21.8946 4.90141 22 5.16663 22C5.43184 22 5.6862 21.8946 5.87373 21.7071C6.06127 21.5196 6.16663 21.2652 6.16663 21V19C6.16663 18.7348 6.06127 18.4804 5.87373 18.2929C5.6862 18.1054 5.43184 18 5.16663 18ZM20.1666 2C19.9014 2 19.6471 2.10536 19.4595 2.29289C19.272 2.48043 19.1666 2.73478 19.1666 3V21C19.1666 21.2652 19.272 21.5196 19.4595 21.7071C19.6471 21.8946 19.9014 22 20.1666 22C20.4318 22 20.6862 21.8946 20.8737 21.7071C21.0613 21.5196 21.1666 21.2652 21.1666 21V3C21.1666 2.73478 21.0613 2.48043 20.8737 2.29289C20.6862 2.10536 20.4318 2 20.1666 2ZM15.1666 9C14.9014 9 14.6471 9.10536 14.4595 9.29289C14.272 9.48043 14.1666 9.73478 14.1666 10V21C14.1666 21.2652 14.272 21.5196 14.4595 21.7071C14.6471 21.8946 14.9014 22 15.1666 22C15.4318 22 15.6862 21.8946 15.8737 21.7071C16.0613 21.5196 16.1666 21.2652 16.1666 21V10C16.1666 9.73478 16.0613 9.48043 15.8737 9.29289C15.6862 9.10536 15.4318 9 15.1666 9Z" fill="white"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 px-2 text-center">
                        <h4 class="text-2xl font-extrabold" style="color:#4682B4;">Gestión de equipo sin esfuerzo</h4>
                        <p class="font-medium" style="color:#708090;">Administra tu equipo de manera sencilla y eficiente con nuestras herramientas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full">
        <div class="container flex flex-col items-center gap-16 mx-auto my-32">
            <div class="flex flex-col gap-16">
                <div class="flex flex-col gap-2 text-center">
                    <h2 class="text-3xl font-extrabold leading-tight lg:text-4xl" style="color:#000000;">¿Cómo trabajamos en ALI3000?</h2>
                    <p class="text-base font-medium leading-7" style="color:#708090;">Nuestra plataforma está diseñada para ofrecer soluciones eficientes y optimizar tu experiencia.</p>
                </div>
            </div>
            <div class="flex flex-col items-center justify-between w-full lg:flex-row gap-y-10 lg:gap-y-0 lg:gap-x-8 xl:gap-x-10">
                <div class="flex items-start gap-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full shrink-0 bg-purple-blue-500">
                        <span class="text-base font-bold leading-7 text-white">1</span>
                    </div>
                    <div class="flex flex-col">
                        <h3 class="text-base font-bold leading-tight" style="color:#4682B4;">Crea tu cuenta</h3>
                        <p class="text-base font-medium leading-7" style="color:#708090;">Únete a nuestra plataforma creando tu cuenta personalizada.</p>
                    </div>
                </div>
                <div class="rotate-90 lg:rotate-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="43" height="42" viewBox="0 0 43 42" fill="none">
                        <g clip-path="url(#clip0_3346_6663)">
                            <path d="M16.9242 11.7425C16.2417 12.425 16.2417 13.5275 16.9242 14.21L23.7142 21L16.9242 27.79C16.2417 28.4725 16.2417 29.575 16.9242 30.2575C17.6067 30.94 18.7092 30.94 19.3917 30.2575L27.4242 22.225C28.1067 21.5425 28.1067 20.44 27.4242 19.7575L19.3917 11.725C18.7267 11.06 17.6067 11.06 16.9242 11.7425Z" fill="#A3AED0"></path>
                        </g>
                        <defs>
                            <clipPath id="clip0_3346_6663">
                                <rect width="42" height="42" fill="white" transform="translate(0.666748)"></rect>
                            </clipPath>
                        </defs>
                    </svg>
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-transparent border-2 border-solid rounded-full shrink-0 text-purple-blue-500 border-purple-blue-500">
                        <span class="text-base font-bold leading-7">2</span>
                    </div>
                    <div class="flex flex-col">
                        <h3 class="text-base font-bold leading-tight" style="color:#4682B4;">Configura tu cuenta</h3>
                        <p class="text-base font-medium leading-7" style="color:#708090;">Configura tu cuenta fácilmente y adáptala a tus necesidades.</p>
                    </div>
                </div>
                <div class="rotate-90 lg:rotate-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="43" height="42" viewBox="0 0 43 42" fill="none">
                        <g clip-path="url(#clip0_3346_6663)">
                            <path d="M16.9242 11.7425C16.2417 12.425 16.2417 13.5275 16.9242 14.21L23.7142 21L16.9242 27.79C16.2417 28.4725 16.2417 29.575 16.9242 30.2575C17.6067 30.94 18.7092 30.94 19.3917 30.2575L27.4242 22.225C28.1067 21.5425 28.1067 20.44 27.4242 19.7575L19.3917 11.725C18.7267 11.06 17.6067 11.06 16.9242 11.7425Z" fill="#A3AED0"></path>
                        </g>
                        <defs>
                            <clipPath id="clip0_3346_6663">
                                <rect width="42" height="42" fill="white" transform="translate(0.666748)"></rect>
                            </clipPath>
                        </defs>
                    </svg>
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-transparent border-2 border-solid rounded-full shrink-0 text-purple-blue-500 border-purple-blue-500">
                        <span class="text-base font-bold leading-7">3</span>
                    </div>
                    <div class="flex flex-col">
                        <h3 class="text-base font-bold leading-tight" style="color:#4682B4;">Comienza a crecer</h3>
                        <p class="text-base font-medium leading-7" style="color:#708090;">Empieza a crecer con ALI3000 y descubre nuevas oportunidades.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
      
    </div>
    <div class="w-full">
        <div class="container flex flex-col items-center gap-16 mx-auto my-32">
            <div class="flex flex-col w-8/12 gap-2">
                <h2 class="text-3xl font-extrabold text-center md:text-4xl" style="color:#000000;">Preguntas frecuentes</h2>
                <p class="text-base font-medium leading-7 text-center" style="color:#708090;">Nuestro equipo ha recopilado las preguntas más frecuentes para brindarte toda la información que necesitas.</p>
            </div>
            <div class="grid w-full grid-cols-1 gap-5 lg:grid-cols-3 md:grid-cols-2">
                <div class="flex flex-col items-start justify-start col-span-1 gap-6 px-8 py-10 rounded-2xl bg-grey-200">
                    <span class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859M12 3v8.25m0 0l-3-3m3 3l3-3"></path>
                        </svg>
                    </span>
                    <div class="flex flex-col items-start gap-2">
                        <p class="text-xl font-extrabold text-dark-grey-900">How long does it typically take to process an order? </p>
                        <p class="text-base font-medium leading-7 text-dark-grey-600">Processing times for orders can vary, but we aim to get your order processed as quickly as possible.</p>
                    </div>
                </div>
                <div class="flex flex-col items-start justify-start col-span-1 gap-6 px-8 py-10 rounded-2xl bg-grey-200">
                    <span class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"></path>
                        </svg>
                    </span>
                    <div class="flex flex-col items-start gap-2">
                        <p class="text-xl font-extrabold text-dark-grey-900">Is there an estimated time frame for order processing? </p>
                        <p class="text-base font-medium leading-7 text-dark-grey-600">Our team strives to process orders efficiently. While exact processing times may differ, we work diligently to fulfill your order promptly.</p>
                    </div>
                </div>
                <div class="flex flex-col items-start justify-start col-span-1 gap-6 px-8 py-10 rounded-2xl bg-grey-200">
                    <span class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75h4.875a2.625 2.625 0 010 5.25H12M8.25 9.75L10.5 7.5M8.25 9.75L10.5 12m9-7.243V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"></path>
                        </svg>
                    </span>
                    <div class="flex flex-col items-start gap-2">
                        <p class="text-xl font-extrabold text-dark-grey-900">Can you tell me about your return policy? </p>
                        <p class="text-base font-medium leading-7 text-dark-grey-600">Of course! Our return policy is designed to provide you with a hassle-free experience. You can review the details of our return policy on our website, and if you have any specific questions, feel free to ask.</p>
                    </div>
                </div>
                <div class="flex flex-col items-start justify-start col-span-1 gap-6 px-8 py-10 rounded-2xl bg-grey-200">
                    <span class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"></path>
                        </svg>
                    </span>
                    <div class="flex flex-col items-start gap-2">
                        <p class="text-xl font-extrabold text-dark-grey-900">How do I contact your customer support team? </p>
                        <p class="text-base font-medium leading-7 text-dark-grey-600">Contacting our customer support team is easy. You can reach out to us through the contact form on our website, send an email to our dedicated support address, or call our customer support hotline. We're here to assist you.</p>
                    </div>
                </div>
                <div class="flex flex-col items-start justify-start col-span-1 gap-6 px-8 py-10 rounded-2xl bg-grey-200">
                    <span class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"></path>
                        </svg>
                    </span>
                    <div class="flex flex-col items-start gap-2">
                        <p class="text-xl font-extrabold text-dark-grey-900">What payment methods do you accept for online orders? </p>
                        <p class="text-base font-medium leading-7 text-dark-grey-600">We accept a variety of payment methods to make your online shopping experience convenient. You can use major credit cards, PayPal, and other secure payment options at checkout.</p>
                    </div>
                </div>
                <div class="flex flex-col items-start justify-start col-span-1 gap-6 px-8 py-10 rounded-2xl bg-grey-200">
                    <span class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"></path>
                        </svg>
                    </span>
                    <div class="flex flex-col items-start gap-2">
                        <p class="text-xl font-extrabold text-dark-grey-900">Are there any discounts or promotions currently available? </p>
                        <p class="text-base font-medium leading-7 text-dark-grey-600">We regularly run promotions and discounts to provide our customers with value. To stay updated on our current offers, please visit our promotions page on the website or subscribe to our newsletter.</p>
                        <p>
                        </p>
                    </div>
                </div>
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