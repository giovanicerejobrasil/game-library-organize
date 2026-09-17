<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<x-head />

<body class="antialiased min-h-screen flex flex-col font-sans bg-[var(--bg-main)] text-[var(--text-main)] selection:bg-[var(--brand-primary)] selection:text-white transition-colors duration-300">
    <!-- Header Component -->
    <x-header variant="landing" />

    <!-- Hero Section -->
    <section class="relative overflow-hidden py-16 lg:py-24 border-b border-[var(--border-color)]">
        <!-- Retro Glow Effect -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-[var(--brand-primary)]/20 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <img src="{{ asset('images/logo/logo-2.png') }}" alt="Game Library Organize Logo" class="mx-auto w-[200px] mb-5">

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] mb-6 shadow-sm motion-safe:animate-pulse">
                <span>Organizador Unificado de Jogos Digitais</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold font-['Ubuntu'] tracking-tight text-[var(--text-main)] max-w-4xl mx-auto leading-tight">
                Todos os seus jogos em um único lugar, visual e aconchegante.
            </h1>

            <p class="mt-6 text-base sm:text-lg text-[var(--text-muted)] max-w-2xl mx-auto font-['Roboto'] leading-relaxed">
                Centralize suas coleções da Steam, Epic Games, GOG, PlayStation, Xbox, Switch e muito mais. Acompanhe progresso, registre horas jogadas e avalie seus títulos favoritos.
            </p>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('games.create') }}" class="px-6 py-3 text-base font-semibold rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] hover:border-[var(--brand-primary)] hover:shadow-[var(--glow-retro)] hover:-translate-y-0.5 transition-all duration-300 cursor-pointer">
                    Adicionar Jogos
                </a>
            </div>

            <!-- Platform & Library Logos Supported -->
            @php
            $libraries = [
            ['name' => 'Steam', 'logo' => 'steam.png'],
            ['name' => 'Epic Games', 'logo' => 'epic-games.png'],
            ['name' => 'GOG Galaxy', 'logo' => 'gog-galaxy.png'],
            ['name' => 'EA App', 'logo' => 'ea-app.png'],
            ['name' => 'Ubisoft Connect', 'logo' => 'ubisoft-connect.png'],
            ['name' => 'Rockstar Games Launcher', 'logo' => 'rockstar-launcher.png'],
            ['name' => 'Amazon Luna', 'logo' => 'amazon-luna.png'],
            ['name' => 'PlayStation Network', 'logo' => 'playstation-network.png'],
            ['name' => 'Xbox', 'logo' => 'xbox.png'],
            ['name' => 'Nintendo E-Shop', 'logo' => 'nintendo-eshop.png'],
            ];
            @endphp

            <div class="mt-14 pt-8 border-t border-[var(--border-color)]/50 flex flex-col items-center justify-center gap-4">
                <span class="text-xs text-[var(--text-muted)] uppercase tracking-wider font-semibold font-['Open_Sans']">
                    As principais bibliotecas digitais:
                </span>
                <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-5">
                    @foreach ($libraries as $lib)
                    <div
                        class="group relative flex items-center justify-center p-2.5 sm:p-3 bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] shadow-sm hover:border-[var(--brand-primary)] hover:shadow-[var(--glow-retro)] hover:-translate-y-0.5 transition-all duration-300 cursor-pointer"
                        title="{{ $lib['name'] }}">
                        <img
                            src="{{ asset('images/libraries/' . $lib['logo']) }}?v={{ @filemtime(public_path('images/libraries/' . $lib['logo'])) ?: time() }}"
                            alt="{{ $lib['name'] }}"
                            class="h-6 sm:h-7 w-auto max-w-[85px] object-contain transition-transform duration-300 group-hover:scale-105"
                            loading="lazy" />
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Cards Grid Preview Section -->
    <section id="demo" class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--text-main)]">
                    Experiência Visual Imersiva
                </h2>
                <p class="text-sm text-[var(--text-muted)] font-['Roboto'] mt-1">
                    Cards personalizados com informações rápidas e acessíveis para você não perder muito tempo procurando.
                </p>
            </div>
        </div>

        <!-- Sample Game Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="group relative flex flex-col bg-[var(--bg-card)] rounded-[var(--radius-md)] overflow-hidden border border-[var(--border-color)] card-retro-hover cursor-pointer transition-all duration-300">
                <div class="relative w-full aspect-[2/3] overflow-hidden bg-black/40">
                    <img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80" alt="Cyber Journey" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg-card)] via-transparent to-black/40"></div>
                    <div class="absolute top-2.5 right-2.5 z-10"><x-game.status-badge status="finished" size="sm" /></div>
                    <div class="absolute top-2.5 left-2.5 z-10"><span class="px-2 py-0.5 text-[11px] font-semibold bg-black/70 text-white rounded-[var(--radius-sm)]">2024</span></div>
                </div>
                <div class="p-3.5 flex flex-col justify-between flex-1 gap-2">
                    <h3 class="font-bold text-sm text-[var(--text-main)] font-['Ubuntu']">Cyber Journey: Reborn</h3>
                    <div class="flex items-center justify-between gap-2 pt-2 border-t border-[var(--border-color)]/60 text-xs">
                        <x-game.star-rating rating="5.0" size="sm" />
                        <span class="px-1.5 py-0.5 text-[10px] bg-[var(--border-color)] text-[var(--text-muted)] rounded-[var(--radius-sm)]">PC / Steam</span>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="group relative flex flex-col bg-[var(--bg-card)] rounded-[var(--radius-md)] overflow-hidden border border-[var(--border-color)] card-retro-hover cursor-pointer transition-all duration-300">
                <div class="relative w-full aspect-[2/3] overflow-hidden bg-black/40">
                    <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=600&q=80" alt="Retro Odyssey" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg-card)] via-transparent to-black/40"></div>
                    <div class="absolute top-2.5 right-2.5 z-10"><x-game.status-badge status="playing" size="sm" /></div>
                    <div class="absolute top-2.5 left-2.5 z-10"><span class="px-2 py-0.5 text-[11px] font-semibold bg-black/70 text-white rounded-[var(--radius-sm)]">2023</span></div>
                </div>
                <div class="p-3.5 flex flex-col justify-between flex-1 gap-2">
                    <h3 class="font-bold text-sm text-[var(--text-main)] font-['Ubuntu']">Chronicles of Eldoria</h3>
                    <div class="flex items-center justify-between gap-2 pt-2 border-t border-[var(--border-color)]/60 text-xs">
                        <x-game.star-rating rating="4.5" size="sm" />
                        <span class="px-1.5 py-0.5 text-[10px] bg-[var(--border-color)] text-[var(--text-muted)] rounded-[var(--radius-sm)]">PlayStation</span>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="group relative flex flex-col bg-[var(--bg-card)] rounded-[var(--radius-md)] overflow-hidden border border-[var(--border-color)] card-retro-hover cursor-pointer transition-all duration-300">
                <div class="relative w-full aspect-[2/3] overflow-hidden bg-black/40">
                    <img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=600&q=80" alt="Neon Velocity" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg-card)] via-transparent to-black/40"></div>
                    <div class="absolute top-2.5 right-2.5 z-10"><x-game.status-badge status="backlog" size="sm" /></div>
                    <div class="absolute top-2.5 left-2.5 z-10"><span class="px-2 py-0.5 text-[11px] font-semibold bg-black/70 text-white rounded-[var(--radius-sm)]">2025</span></div>
                </div>
                <div class="p-3.5 flex flex-col justify-between flex-1 gap-2">
                    <h3 class="font-bold text-sm text-[var(--text-main)] font-['Ubuntu']">Neon Velocity: Circuit</h3>
                    <div class="flex items-center justify-between gap-2 pt-2 border-t border-[var(--border-color)]/60 text-xs">
                        <x-game.star-rating rating="0.0" size="sm" :showNumber="false" />
                        <span class="px-1.5 py-0.5 text-[10px] bg-[var(--border-color)] text-[var(--text-muted)] rounded-[var(--radius-sm)]">Xbox</span>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="group relative flex flex-col bg-[var(--bg-card)] rounded-[var(--radius-md)] overflow-hidden border border-[var(--border-color)] card-retro-hover cursor-pointer transition-all duration-300">
                <div class="relative w-full aspect-[2/3] overflow-hidden bg-black/40">
                    <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=600&q=80" alt="Dungeon Quest" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg-card)] via-transparent to-black/40"></div>
                    <div class="absolute top-2.5 right-2.5 z-10"><x-game.status-badge status="dropped" size="sm" /></div>
                    <div class="absolute top-2.5 left-2.5 z-10"><span class="px-2 py-0.5 text-[11px] font-semibold bg-black/70 text-white rounded-[var(--radius-sm)]">2022</span></div>
                </div>
                <div class="p-3.5 flex flex-col justify-between flex-1 gap-2">
                    <h3 class="font-bold text-sm text-[var(--text-main)] font-['Ubuntu']">Shadows of the Abyss</h3>
                    <div class="flex items-center justify-between gap-2 pt-2 border-t border-[var(--border-color)]/60 text-xs">
                        <x-game.star-rating rating="3.0" size="sm" />
                        <span class="px-1.5 py-0.5 text-[10px] bg-[var(--border-color)] text-[var(--text-muted)] rounded-[var(--radius-sm)]">Switch</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-16 bg-[var(--bg-card)] border-t border-[var(--border-color)] transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-center text-[var(--text-main)] mb-8">
                Dúvidas Frequentes (FAQ)
            </h2>

            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <div class="p-5 sm:p-6 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] shadow-sm">
                    <h3 class="font-bold text-base text-[var(--text-main)] font-['Open_Sans'] mb-2">
                        O que é o Game Library Organize?
                    </h3>
                    <p class="text-sm text-[var(--text-muted)] font-['Roboto'] leading-relaxed">
                        É um sistema unificado para você organizar, catalogar, acompanhar o progresso e avaliar seus jogos digitais comprados em múltiplas plataformas como Steam, Epic Games, GOG, PlayStation, Xbox e Nintendo Switch.
                    </p>
                </div>

                <!-- FAQ Item 2 -->
                <div class="p-5 sm:p-6 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] shadow-sm">
                    <h3 class="font-bold text-base text-[var(--text-main)] font-['Open_Sans'] mb-2">
                        Posso personalizar as cores e temas?
                    </h3>
                    <p class="text-sm text-[var(--text-muted)] font-['Roboto'] leading-relaxed">
                        Sim! O sistema possui suporte nativo a Modo Escuro (Dark) e Claro (Light), além de permitir que cada usuário personalize as cores primária e secundária do seu dashboard através de variáveis CSS dinâmicas.
                    </p>
                </div>

                <!-- FAQ Item 3 -->
                <div class="p-5 sm:p-6 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] shadow-sm">
                    <h3 class="font-bold text-base text-[var(--text-main)] font-['Open_Sans'] mb-2">
                        Como funciona a busca por jogos?
                    </h3>
                    <p class="text-sm text-[var(--text-muted)] font-['Roboto'] leading-relaxed">
                        A busca utiliza o mecanismo de alta performance Elasticsearch, permitindo filtros combinados por nome, desenvolvedora, publicadora, ano, gênero, plataformas e status com autocomplete instantâneo.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Component -->
    <x-footer variant="landing" />
</body>

</html>