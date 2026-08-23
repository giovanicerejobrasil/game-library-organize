@props([
'variant' => 'landing',
'sticky' => true,
'logoVariant' => null,
'showThemeSwitcher' => true,
])

@php
$resolvedLogoVariant = $logoVariant ?? ($variant === 'app' ? 'icon' : 'main');
$stickyClass = $sticky ? 'sticky top-0 z-50 backdrop-blur-md' : 'relative z-10';

$logoDarkUrl = asset('images/logo/logo-dark-mode.png') . '?v=' . (@filemtime(public_path('images/logo/logo-dark-mode.png')) ?: time());
$logoLightUrl = asset('images/logo/logo-light-mode.png') . '?v=' . (@filemtime(public_path('images/logo/logo-light-mode.png')) ?: time());
$logoIconUrl = asset('images/logo/logo-2.png') . '?v=' . (@filemtime(public_path('images/logo/logo-2.png')) ?: time());
$iconIcoUrl = asset('images/logo/icon.ico') . '?v=' . (@filemtime(public_path('images/logo/icon.ico')) ?: time());
@endphp

<header {{ $attributes->merge(['class' => "{$stickyClass} bg-[var(--bg-card)]/80 border-b border-[var(--border-color)] transition-colors duration-300"]) }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-18 flex items-center justify-between gap-4">
        <!-- Logo & Brand Link -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 group transition-transform duration-200 hover:scale-[1.02]">
            @if ($resolvedLogoVariant === 'main')
            <div class="flex items-center gap-3">
                <img src="{{ $logoDarkUrl }}" alt="{{ config('app.name', 'Game Library Organize') }}" class="logo-dark-mode h-9 sm:h-10 w-auto object-contain" onerror="this.src='{{ $iconIcoUrl }}'">
                <img src="{{ $logoLightUrl }}" alt="{{ config('app.name', 'Game Library Organize') }}" class="logo-light-mode h-9 sm:h-10 w-auto object-contain" onerror="this.src='{{ $iconIcoUrl }}'">
            </div>
            @else
            <div class="flex items-center gap-3">
                <img src="{{ $logoIconUrl }}" alt="{{ config('app.name', 'Game Library Organize') }}" class="h-9 w-auto rounded object-contain transition-transform duration-200 group-hover:scale-105" onerror="this.src='{{ $iconIcoUrl }}'">
                <span class="font-bold text-lg text-[var(--text-main)] font-['Ubuntu'] tracking-tight hidden sm:inline">Game Library Organize</span>
            </div>
            @endif
        </a>

        <!-- Center Slot (Search bar, Navigation links) -->
        @if (isset($nav))
        <nav class="hidden md:flex items-center gap-6 text-sm font-medium font-['Open_Sans']">
            {{ $nav }}
        </nav>
        @else
        {{ $slot ?? '' }}
        @endif

        <!-- Right Side: Actions, Theme Switcher & User Profile -->
        <div class="flex items-center gap-3 sm:gap-4">
            @if ($showThemeSwitcher)
            <x-theme-switcher />
            @endif

            @if (isset($actions))
            {{ $actions }}
            @elseif ($variant === 'landing')
            <a href="#faq" class="text-sm font-medium text-[var(--text-muted)] hover:text-[var(--text-main)] transition-colors hidden sm:inline font-['Open_Sans']">
                Dúvidas Frequentes
            </a>
            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white shadow-md hover:opacity-90 transition-all font-['Open_Sans']">
                Acessar Dashboard
            </a>
            @elseif ($variant === 'app')
            @auth
            <div class="flex items-center gap-3 border-l border-[var(--border-color)] pl-4">
                <span class="text-sm font-medium text-[var(--text-main)] hidden md:inline">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 rounded-full bg-[var(--brand-primary)] text-white font-bold text-xs flex items-center justify-center shadow-sm">
                    {{ auth()->user()->initials() }}
                </div>
            </div>
            @else
            <div class="flex items-center gap-2">
                <a href="#" class="px-3 py-1.5 text-sm font-medium text-[var(--text-main)] hover:text-[var(--brand-primary)] transition-colors">Entrar</a>
                <a href="#" class="px-3 py-1.5 text-sm font-medium bg-[var(--brand-primary)] text-white rounded-md hover:opacity-90 transition-opacity">Cadastrar</a>
            </div>
            @endauth
            @endif
        </div>
    </div>
</header>