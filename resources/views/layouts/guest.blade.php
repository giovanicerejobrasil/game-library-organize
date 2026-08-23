<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<x-head :title="$title ?? null" :includeLivewire="true" />

<body class="antialiased min-h-screen flex flex-col bg-[var(--bg-main)] text-[var(--text-main)] font-sans transition-colors duration-300">
    <!-- Header Component (Guest Variant) -->
    <x-header variant="guest" :sticky="false" />

    <!-- Main Content -->
    <main class="flex-1 flex flex-col justify-center items-center px-4 py-8">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer Component -->
    <x-footer variant="guest" />

    @livewireScripts
</body>
</html>
