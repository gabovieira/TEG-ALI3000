<?php ini_set('display_errors', 1); error_reporting(E_ALL); echo "<div style='color:red;font-size:2em;'>PRUEBA ERROR: ".$variable_que_no_existe."</div>"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | ALI 3000</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">
<!-- Loader pantalla completa -->
<?php include __DIR__ . '/../compartido/loader.php'; ?>
<div class="flex min-h-screen h-screen overflow-hidden">
    <!-- Sidebar Moderno -->
    <?php 
        $menu_activo = 'dashboard'; 
        include __DIR__ . '/../compartido/sidebar-admin.php'; 
    ?>
    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-h-0 overflow-y-auto">
        <!-- Header Superior -->
        <?php 
            $titulo_header = 'Dashboard';
            include __DIR__ . '/../compartido/header-admin.php'; 
        ?>
        <!-- Aquí solo visualización de datos, las variables deben venir del controlador -->
        <header class="flex items-center justify-between px-8 py-4 bg-white border-b border-gray-200 shadow-sm">
            <div class="flex items-center space-x-2">
                <span class="text-gray-400 text-sm font-semibold">ALI 3000</span>
                <span class="mx-2 text-gray-300">›</span>
                <span class="text-gray-900 font-bold">Dashboard</span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-blue-700 font-semibold">Tasa BCV:</span>
                <span class="font-bold text-gray-900"><?php echo $tasa_bcv_texto; ?></span>
                <span class="text-xs text-gray-500 ml-2">actualizado</span>
            </div>
        </header>
        <!-- Frase de quincena actual arriba del dashboard -->
        <section class="mb-4 flex flex-col items-center justify-center">
            <?php
            $feriados_bcv = [
                '2025-06-19',
                '2025-06-24',
            ];
            $hoy = new DateTime();
            $anio = $hoy->format('Y');
            $mes = $hoy->format('n');
            $dia = $hoy->format('j');
            $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
            if ($dia <= 15) {
                $inicio = 1;
                $fin = 15;
            } else {
                $inicio = 16;
                $fin = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
            }
            // Calcular último día hábil (no sábado, domingo ni feriado BCV)
            $ultimo_habil = $fin;
            while (in_array((new DateTime("$anio-$mes-$ultimo_habil"))->format('N'), [6,7]) || in_array((new DateTime("$anio-$mes-$ultimo_habil"))->format('Y-m-d'), $feriados_bcv)) {
                $ultimo_habil--;
            }
            $nombre_mes = $meses[(int)$mes];
            echo '<div class="text-base text-gray-700 text-center mb-2">Quincena actual: <span class="font-semibold">'.$inicio.' al '.$fin.' de '.$nombre_mes.' '.$anio.'</span> &bull; Último día hábil para subir horas: <span class="bg-green-100 px-2 py-0.5 rounded">'.$ultimo_habil.' de '.$nombre_mes.'</span></div>';
            ?>
        </section>
        <!-- Calendario de quincena actual (centrado, con nombre de día) -->
        <section class="mb-10 flex flex-col items-center justify-center">
            <?php
            $dias_semana = ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'];
            echo '<div class="flex flex-row gap-2 justify-center">';
            for ($d = $inicio; $d <= $fin; $d++) {
                $fecha = new DateTime("$anio-$mes-$d");
                $es_hoy = ($d == $dia);
                $es_ultimo_habil = ($d == $ultimo_habil);
                $es_finde = in_array($fecha->format('N'), [6,7]);
                $es_feriado = in_array($fecha->format('Y-m-d'), $feriados_bcv);
                $clases = 'flex flex-col items-center justify-center w-14 h-20 rounded-lg border text-sm relative cursor-default';
                if ($es_hoy) $clases .= ' bg-blue-100 border-blue-400 text-blue-800 font-bold shadow';
                elseif ($es_ultimo_habil) $clases .= ' bg-green-100 border-green-400 text-green-800 font-bold shadow animate-pulse';
                elseif ($es_feriado) $clases .= ' bg-yellow-100 border-yellow-400 text-yellow-700 line-through';
                elseif ($es_finde) $clases .= ' bg-gray-100 text-gray-400';
                else $clases .= ' bg-white';
                echo '<div class="'.$clases.'" title="'.($es_feriado ? 'Feriado BCV' : ($es_finde ? 'Fin de semana' : 'Día hábil')).'">';
                echo '<span class="text-xs text-gray-500">'.$dias_semana[$fecha->format('w')].'</span>';
                echo '<span class="text-lg">'.$d.'</span>';
                if ($es_ultimo_habil) echo '<span class="text-xs mt-1 bg-green-200 px-2 py-0.5 rounded-full absolute bottom-1 left-1/2 -translate-x-1/2">Último hábil</span>';
                if ($es_hoy) echo '<span class="text-xs mt-1 bg-blue-200 px-2 py-0.5 rounded-full absolute bottom-1 left-1/2 -translate-x-1/2">Hoy</span>';
                if ($es_feriado) echo '<span class="text-xs mt-1 text-yellow-700">Feriado</span>';
                echo '</div>';
            }
            echo '</div>';
            ?>
            <div class="mt-2 flex flex-wrap gap-4 text-xs text-gray-500 justify-center">
                <span><span class="inline-block w-3 h-3 bg-green-200 rounded-full mr-1 align-middle"></span>Último día hábil</span>
                <span><span class="inline-block w-3 h-3 bg-blue-200 rounded-full mr-1 align-middle"></span>Hoy</span>
                <span><span class="inline-block w-3 h-3 bg-yellow-200 rounded-full mr-1 align-middle"></span>Feriado BCV</span>
                <span><span class="inline-block w-3 h-3 bg-gray-200 rounded-full mr-1 align-middle"></span>Fin de semana</span>
            </div>
        </section>
        <div class="flex-1 p-6">
            <!-- Métricas principales -->
            <!-- Cards de métricas principales (vacías, para datos dinámicos) -->
            <div id="dashboard-main" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-4 rounded-lg shadow-md flex flex-col">
                    <div class="text-gray-500 text-sm">Consultores Activos</div>
                    <div class="text-2xl font-bold" id="consultores-activos"><?php echo $consultores_activos_sidebar; ?></div>
                    <div class="text-xs text-green-600">&nbsp;</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md flex flex-col">
                    <div class="text-gray-500 text-sm">Horas Pendientes de Validación</div>
                    <div class="text-2xl font-bold" id="horas-pendientes">--</div>
                    <div class="text-xs text-orange-500">&nbsp;</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md flex flex-col">
                    <div class="text-gray-500 text-sm">Horas Validadas Pendientes de Pago</div>
                    <div class="text-2xl font-bold" id="horas-por-pagar">--</div>
                    <div class="text-xs text-blue-600">&nbsp;</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md flex flex-col">
                    <div class="text-gray-500 text-sm">Total Pagado Este Mes</div>
                    <div class="text-2xl font-bold" id="pagado-mes">--</div>
                    <div class="text-xs text-gray-500">&nbsp;</div>
                </div>
            </div>
            <!-- Eliminar la sección de usuarios activos (usuarios-main) -->
            <!-- Alertas y notificaciones (vacías) -->
            <div class="space-y-4 mb-8" id="alertas-dashboard"></div>
            <!-- Acciones rápidas (sin datos fijos) -->
            <div class="flex flex-wrap gap-4 mb-8">
                <button class="bg-gradient-to-r from-blue-600 to-orange-500 text-white font-bold py-3 px-6 rounded-lg shadow hover:scale-105 transition text-lg flex items-center">
                    🏦 Procesar Pagos Banesco <span class="ml-2 bg-white text-blue-600 rounded-full px-2 py-0.5 text-xs font-bold" id="horas-por-pagar-btn">--</span>
                </button>
                <button class="bg-white border border-gray-200 text-blue-700 font-semibold py-3 px-6 rounded-lg shadow hover:bg-blue-50 transition flex items-center">
                    👥 Crear Usuario
                </button>
                <button class="bg-white border border-gray-200 text-blue-700 font-semibold py-3 px-6 rounded-lg shadow hover:bg-blue-50 transition flex items-center">
                    🏢 Asignar Consultor a Empresa
                </button>
                <button class="bg-white border border-gray-200 text-blue-700 font-semibold py-3 px-6 rounded-lg shadow hover:bg-blue-50 transition flex items-center">
                    ⚙️ Configurar Parámetros
                </button>
                <button class="bg-green-100 border border-green-200 text-green-700 font-semibold py-3 px-6 rounded-lg shadow hover:bg-green-200 transition flex items-center">
                    📱 Enviar Recordatorio WhatsApp
                </button>
            </div>
            <!-- Tabla: Horas Pendientes de Pago (vacía) -->
            <section class="mb-10">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Horas Pendientes de Pago</h2>
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full bg-white text-gray-800 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Consultor</th>
                                <th class="px-4 py-2 text-left">Empresa</th>
                                <th class="px-4 py-2 text-left">Período</th>
                                <th class="px-4 py-2 text-right">Horas</th>
                                <th class="px-4 py-2 text-right">Monto USD</th>
                                <th class="px-4 py-2 text-right">Monto Bs</th>
                                <th class="px-4 py-2 text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-horas-pago">
                            <tr><td colspan="7" class="text-center text-gray-400 py-6">Sin datos</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <!-- Tabla: Usuarios Recientes (vacía) -->
            <section class="mb-10">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Usuarios Recientes</h2>
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full bg-white text-gray-800 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Código</th>
                                <th class="px-4 py-2 text-left">Nombre</th>
                                <th class="px-4 py-2 text-left">Tipo</th>
                                <th class="px-4 py-2 text-left">Empresa</th>
                                <th class="px-4 py-2 text-left">Estado</th>
                                <th class="px-4 py-2 text-left">Creado</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-usuarios-recientes">
                            <tr><td colspan="6" class="text-center text-gray-400 py-6">Sin datos</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <!-- Visualizaciones y gráficos (placeholders vacíos) -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center min-h-[220px] border border-gray-100 text-gray-400">[Gráfico de barras]</div>
                <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center min-h-[220px] border border-gray-100 text-gray-400">[Gráfico de líneas]</div>
                <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center min-h-[220px] border border-gray-100 text-gray-400">[Gráfico de dona]</div>
                <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center justify-center min-h-[220px] border border-gray-100 text-gray-400">[Heatmap]</div>
            </section>
        </div>
        <!-- Footer -->
        <footer class="mt-auto px-6 py-4 bg-white border-t border-gray-200 text-xs text-gray-500 text-center">
            ALI 3000 &copy; 2025. Dashboard desarrollado para gestión de consultores y pagos.
        </footer>
    </main>
</div>
<!-- Modal Crear Usuario (mejorado visualmente) -->
<div id="modal-crear-usuario" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-all duration-200 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl p-8 relative border border-blue-100 animate-fade-in">
        <button onclick="document.getElementById('modal-crear-usuario').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-3xl font-bold transition">&times;</button>
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-green-400 flex items-center justify-center text-2xl font-bold mr-3 shadow">👤</div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Crear Usuario</h2>
        </div>
        <form id="form-crear-usuario" method="post" action="index.php?controller=admin&action=crearUsuario" class="space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Primer Nombre *</label>
                    <input type="text" name="primer_nombre" required class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2 bg-gray-50" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2 bg-gray-50" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Primer Apellido *</label>
                    <input type="text" name="primer_apellido" required class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2 bg-gray-50" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Segundo Apellido</label>
                    <input type="text" name="segundo_apellido" class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2 bg-gray-50" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Cédula *</label>
                    <input type="text" name="cedula" required class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2 bg-gray-50" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">RIF</label>
                    <input type="text" name="rif" class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2 bg-gray-50" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Sexo *</label>
                    <select name="sexo" required class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2 bg-gray-50">
                        <option value="">Seleccione</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo *</label>
                    <select name="tipo_usuario" required class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2 bg-gray-50" onchange="mostrarCamposConsultor(this.value)">
                        <option value="">Seleccione</option>
                        <option value="consultor">Consultor</option>
                        <option value="validador">Validador</option>
                    </select>
                </div>
            </div>
            <div id="campos-consultor" class="grid grid-cols-2 gap-4 hidden">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tarifa por Hora (USD) *</label>
                    <input type="number" step="0.01" name="tarifa_por_hora" class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2 bg-gray-50" />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nivel de Desarrollo *</label>
                    <select name="nivel_desarrollo" class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2 bg-gray-50">
                        <option value="">Seleccione</option>
                        <option value="junior">Junior</option>
                        <option value="semi-senior">Semi-Senior</option>
                        <option value="senior">Senior</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Empresa (opcional)</label>
                <input type="text" name="empresa" class="mt-1 block w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2 bg-gray-50" placeholder="Nombre de la empresa" />
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-gradient-to-r from-blue-600 to-green-400 hover:from-blue-700 hover:to-green-500 text-white font-bold py-2 px-8 rounded-lg shadow-lg transition-all text-base">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>
<?php if (isset($_GET['exito']) && isset($_GET['codigo'])): ?>
<script>
window.addEventListener('DOMContentLoaded', function() {
    // Crear alerta flotante
    let alerta = document.createElement('div');
    alerta.className = 'fixed bottom-8 right-8 z-50 bg-green-100 border border-green-300 text-green-800 rounded-xl px-6 py-4 shadow-lg flex flex-col items-center max-w-xs animate-fade-in';
    alerta.innerHTML = `
        <div class='text-lg font-bold mb-1'>¡Usuario creado exitosamente!</div>
        <div class='mb-2'>El código de registro es:</div>
        <div class='text-2xl font-mono font-bold bg-white px-4 py-2 rounded border border-green-200 mb-2'><?php echo htmlspecialchars($_GET['codigo']); ?></div>
        <div class='mb-2 text-gray-700 text-sm'>Comparte este código con el usuario para que pueda registrarse en el sistema.</div>
        <a href="https://wa.me/?text=Tu%20c%C3%B3digo%20de%20registro%20en%20ALI%203000%20es%20<?php echo urlencode($_GET['codigo']); ?>" target="_blank" class="mt-2 bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded-lg shadow flex items-center gap-2 transition">
            <span>Enviar por WhatsApp</span>
            <svg class='w-5 h-5 ml-1' fill='currentColor' viewBox='0 0 24 24'><path d="M20.52 3.48A12 12 0 0 0 3.48 20.52l-1.32 4.84a1 1 0 0 0 1.22 1.22l4.84-1.32A12 12 0 1 0 20.52 3.48zm-8.52 17A9 9 0 1 1 19 12a9 9 0 0 1-7 8.48zm4.29-6.7c-.2-.1-1.18-.58-1.36-.65s-.32-.1-.45.1-.52.65-.64.78-.24.15-.44.05a7.36 7.36 0 0 1-2.18-1.35 8.23 8.23 0 0 1-1.52-1.88c-.16-.27-.02-.41.12-.56.13-.13.3-.34.45-.51a.48.48 0 0 0 .1-.5c0-.14-.45-1.08-.62-1.48s-.33-.34-.45-.35h-.38a.73.73 0 0 0-.53.25A2.19 2.19 0 0 0 6.7 13.7c0 1.13.82 2.22 2.34 3.06a8.13 8.13 0 0 0 4.13 1.13c.86 0 1.7-.13 2.5-.38a2.13 2.13 0 0 0 1.36-1.36c.1-.27.1-.5.07-.55s-.18-.13-.38-.23z"/></svg>
        </a>
        <button onclick='this.parentNode.remove()' class='absolute top-2 right-3 text-gray-400 hover:text-red-500 text-2xl font-bold'>&times;</button>
    `;
    document.body.appendChild(alerta);
    setTimeout(() => { alerta.remove(); window.history.replaceState({}, document.title, window.location.pathname); }, 300000); // 5 minutos
});
</script>
<?php endif; ?>
<style>
@keyframes fade-in { from { opacity: 0; transform: scale(0.98);} to { opacity: 1; transform: scale(1);} }
.animate-fade-in { animation: fade-in 0.2s ease; }
</style>
<script>
// Mostrar campos de consultor solo si el tipo es consultor
function mostrarCamposConsultor(tipo) {
    var campos = document.getElementById('campos-consultor');
    if (tipo === 'consultor') {
        campos.classList.remove('hidden');
    } else {
        campos.classList.add('hidden');
    }
}
// Mostrar modal al hacer clic en el botón principal
// Solo abrir modal, NO prevenir el submit del formulario
// El submit debe funcionar normalmente

document.addEventListener('DOMContentLoaded', function() {
    var btns = document.querySelectorAll('button, a');
    btns.forEach(function(btn) {
        if (btn.textContent.includes('Crear Usuario')) {
            btn.addEventListener('click', function(e) {
                // Solo abrir modal si el botón NO es el submit del formulario
                if (!btn.closest('form')) {
                    e.preventDefault();
                    document.getElementById('modal-crear-usuario').classList.remove('hidden');
                }
            });
        }
    });
});
</script>
<script>
window.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var loader = document.getElementById('loader');
        if(loader) {
            loader.style.opacity = '0';
            setTimeout(function() {
                loader.style.display = 'none';
            }, 200); // más rápido para evitar parpadeo
        }
    }, 400); // menos tiempo para evitar overlay molesto
});
</script>
</main>
</div>
</body>
</html>