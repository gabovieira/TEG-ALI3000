<!-- Loader pantalla completa reutilizable -->
<div id="loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-300">
    <div class="flex flex-col items-center">
        <img src="/ali3000/assets/img/logoali3000.png" alt="Logo ALI 3000" class="w-32 h-32 mb-6 animate-pulse" />
        <span class="text-2xl font-extrabold text-blue-900 mb-2 tracking-wide">ALI 3000 CONSULTORES</span>
        <svg class="animate-spin h-12 w-12 text-blue-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        <span class="text-lg font-semibold text-blue-700">Cargando...</span>
    </div>
</div>
<script>
window.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.getElementById('loader').style.opacity = '0';
        setTimeout(function() {
            document.getElementById('loader').style.display = 'none';
        }, 300);
    }, 800);
});
</script>
