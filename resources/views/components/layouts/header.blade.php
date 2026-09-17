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
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="px-4 py-2 text-sm font-semibold rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white shadow-md hover:opacity-90 transition-all font-['Open_Sans']">
                {{ auth()->check() ? 'Minha Biblioteca' : 'Entrar' }}
            </a>
            @elseif ($variant === 'app')
            @auth
            <div class="flex items-center gap-3 border-l border-[var(--border-color)] pl-4" x-data="{ open: false }">
                <div class="relative">
                    <button @click="open = !open" @click.away="open = false" type="button" class="flex items-center gap-2.5 hover:opacity-80 transition-opacity focus:outline-none cursor-pointer">
                        <span class="text-sm font-medium text-[var(--text-main)] hidden md:inline font-['Open_Sans']">{{ auth()->user()->name }}</span>
                        <div class="w-8 h-8 rounded-full bg-[var(--brand-primary)] text-white font-bold text-xs flex items-center justify-center shadow-sm">
                            {{ auth()->user()->initials() }}
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] shadow-[var(--elevation-high)] py-1.5 z-50 text-xs font-['Open_Sans']">
                        <div class="px-3.5 py-2 border-b border-[var(--border-color)]">
                            <p class="font-semibold text-[var(--text-main)] truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[var(--text-muted)] truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3.5 py-2 text-[var(--text-main)] hover:bg-[var(--border-color)]/30 transition-colors">
                            <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Minha Biblioteca
                        </a>
                        <a href="{{ route('games.create') }}" class="flex items-center gap-2 px-3.5 py-2 text-[var(--text-main)] hover:bg-[var(--border-color)]/30 transition-colors">
                            <svg class="w-4 h-4 text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Adicionar Jogo
                        </a>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3.5 py-2 text-[var(--text-main)] hover:bg-[var(--border-color)]/30 transition-colors">
                            <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Configurações
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2 px-3.5 py-2 text-[var(--status-dropped)] hover:bg-[var(--border-color)]/30 transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Sair da Conta
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @else
            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="px-3 py-1.5 text-sm font-medium text-[var(--text-main)] hover:text-[var(--brand-primary)] transition-colors">Entrar</a>
                <a href="{{ route('register') }}" class="px-3 py-1.5 text-sm font-medium bg-[var(--brand-primary)] text-white rounded-md hover:opacity-90 transition-opacity">Cadastrar</a>
            </div>
            @endauth
            @endif
        </div>
    </div>
</header>