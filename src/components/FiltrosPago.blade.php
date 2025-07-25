{{-- FiltrosPago: Filtros reutilizables para tablas y reportes --}}
<div class="flex flex-wrap gap-4 mb-4">
    <select name="consultor" class="form-select">
        <option value="">Todos los consultores</option>
        @foreach($consultores as $consultor)
            <option value="{{ $consultor->id }}">{{ $consultor->nombre }}</option>
        @endforeach
    </select>
    <select name="empresa" class="form-select">
        <option value="">Todas las empresas</option>
        @foreach($empresas as $empresa)
            <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
        @endforeach
    </select>
    <select name="estado" class="form-select">
        <option value="">Todos los estados</option>
        <option value="pendiente">Pendiente</option>
        <option value="pagado">Pagado</option>
        <option value="anulado">Anulado</option>
    </select>
    <input type="text" name="periodo" class="form-input" placeholder="Período quincenal">
    <button type="submit" class="btn btn-primary">Filtrar</button>
</div>
