@extends('layouts.admin')

@section('title', 'Crear Consultor - ALI3000')

@section('admin-content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#000000]">Crear Nuevo Consultor</h1>
    <p class="text-[#708090]">Registra un nuevo consultor en el sistema</p>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-[#708090]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-[#000000]">Módulo en Desarrollo</h3>
        <p class="mt-1 text-sm text-[#708090]">La creación de consultores estará disponible próximamente</p>
        <div class="mt-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#4682B4] hover:bg-blue-600">
                Volver al Dashboard
            </a>
        </div>
    </div>
</div>
@endsection