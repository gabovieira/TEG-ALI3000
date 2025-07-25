@extends('layouts.admin')

@section('title', 'Gestión de Consultores - ALI3000')

@php
    $pageTitle = 'Gestión de Consultores';
    $pageDescription = 'Administra los consultores del sistema';
    $breadcrumbs = [
        ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['name' => 'Consultores', 'url' => '']
    ];
@endphp

@section('admin-content')
<!-- Placeholder para vista de consultores -->
<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
    <h2 class="text-xl font-semibold text-[#000000] mb-4">Lista de Consultores</h2>
    <p class="text-[#708090]">Vista en desarrollo - Aquí se mostrará la lista de consultores</p>
</div>
@endsection