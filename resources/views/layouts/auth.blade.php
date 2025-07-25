@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo -->
        <div class="text-center">
            <img class="mx-auto h-20 w-auto" src="{{ asset('assets/img/logoali3000.svg') }}" alt="ALI3000 Logo">
            <h2 class="mt-6 text-3xl font-extrabold text-[#000000]">
                @yield('auth-title', 'ALI3000 Consultores')
            </h2>
            <p class="mt-2 text-sm text-[#708090]">
                @yield('auth-subtitle', 'Sistema de Gestión de Pagos')
            </p>
        </div>
        
        <!-- Alerts -->
        @include('layouts.partials.alerts')
        
        <!-- Auth Content -->
        @yield('auth-content')
    </div>
</div>
@endsection