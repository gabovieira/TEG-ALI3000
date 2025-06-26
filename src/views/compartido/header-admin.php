<?php
// Header reutilizable para admin
?>
<header class="flex items-center justify-between px-8 py-4 bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center space-x-2">
        <span class="text-gray-400 text-sm font-semibold">ALI 3000</span>
        <span class="mx-2 text-gray-300">›</span>
        <span class="text-gray-900 font-bold"><?php echo isset($titulo_header) ? $titulo_header : 'Dashboard'; ?></span>
    </div>
</header>
