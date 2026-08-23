@props([
    'title' => null,
    'description' => 'Game Library Organize - Centralize, organize e acompanhe sua biblioteca de jogos adquiridos em múltiplas plataformas.',
    'favicon' => null,
    'includeLivewire' => false,
])

@php
    $pageTitle = $title ? "{$title} — " . config('app.name', 'Game Library Organize') : config('app.name', 'Game Library Organize') . ' — Organize sua Biblioteca de Jogos';
    $faviconUrl = $favicon ?? asset('images/logo/icon.ico') . '?v=' . (@filemtime(public_path('images/logo/icon.ico')) ?: time());
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description }}">

    <title>{{ $pageTitle }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">

    <!-- Pre-render Theme Anti-Flickering Script -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('glo_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);

            const brandPrimary = localStorage.getItem('glo_brand_primary');
            const brandSecondary = localStorage.getItem('glo_brand_secondary');

            if (brandPrimary) {
                document.documentElement.style.setProperty('--brand-primary', brandPrimary);
            }
            if (brandSecondary) {
                document.documentElement.style.setProperty('--brand-secondary', brandSecondary);
            }
        })();
    </script>

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if ($includeLivewire)
        @livewireStyles
    @endif

    {{ $slot ?? '' }}
</head>
