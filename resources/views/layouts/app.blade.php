<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ auth()->user()?->theme?->value ?? 'dark' }}" style="--brand-primary: {{ auth()->user()?->brand_primary ?? '#182075' }}; --brand-secondary: {{ auth()->user()?->brand_secondary ?? '#751919' }};">

<x-head :title="$title ?? null" :includeLivewire="true" />

<body class="antialiased min-h-screen flex flex-col font-sans bg-[var(--bg-main)] text-[var(--text-main)] transition-colors duration-300">
    <!-- Header Component (App Variant with icon logo) -->
    <x-header variant="app" :logoVariant="'icon'">
        @if (isset($nav))
            <x-slot:nav>{{ $nav }}</x-slot:nav>
        @endif
        @if (isset($actions))
            <x-slot:actions>{{ $actions }}</x-slot:actions>
        @endif
    </x-header>

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer Component -->
    <x-footer variant="app" />

    @livewireScripts
</body>
</html>
