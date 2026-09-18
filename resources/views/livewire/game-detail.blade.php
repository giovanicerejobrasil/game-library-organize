<div class="min-h-screen pb-16 bg-[var(--bg-main)] text-[var(--text-main)] transition-colors duration-300">
    @php
    $coverUrl = $game->cover_image ?? 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80';
    $bgUrl = $game->background_image ?? $coverUrl;

    // Resolução da Classificação Indicativa do Brasil (ClassInd) via AgeRating Enum
    $ageRating = $game->age_rating instanceof \App\Enums\AgeRating
    ? $game->age_rating
    : \App\Enums\AgeRating::tryFromLenient(is_string($game->age_rating) ? $game->age_rating : null);

    $ratingData = $ageRating ? [
    'bg' => $ageRating->colors()['bg'],
    'text' => $ageRating->colors()['text'],
    'label' => $ageRating->shortLabel(),
    'full_label' => $ageRating->label(),
    ] : null;

    // Mapeamento das Cores Oficiais das Plataformas e Bibliotecas Digitais
    $platformColor = function (string $name, string $slug): array {
    return \App\Models\Platform::resolveColors($name, $slug);
    };

    $libraryColor = function (string $name, string $slug): array {
    return \App\Models\GameLibrary::resolveColors($name, $slug);
    };
    @endphp

    <!-- Hero Banner Background with High Contrast and Visibility -->
    <div class="relative w-full h-72 sm:h-88 md:h-[420px] lg:h-[460px] overflow-hidden bg-black">
        <!-- Background Image Sharp & Apparent -->
        <img
            src="{{ $bgUrl }}"
            alt="{{ $game->title }} Wallpaper"
            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=1920&q=80';"
            class="w-full h-full object-cover object-center opacity-70 scale-100 pointer-events-none transition-opacity duration-300" />

        <!-- Gradient Overlay Allowing Wallpaper to Shine Through -->
        <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg-main)] via-[var(--bg-main)]/35 to-black/50 pointer-events-none"></div>

        <!-- Top Breadcrumbs & Back Navigation -->
        <div class="absolute top-6 left-0 right-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-20 flex items-center justify-between">
            <a
                href="{{ route('dashboard') }}"
                wire:navigate
                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-[var(--radius-md)] bg-[var(--bg-card)]/90 hover:bg-[var(--bg-card)] border border-[var(--border-color)] text-xs font-semibold text-[var(--text-main)] font-['Open_Sans'] backdrop-blur-md shadow-md transition-all hover:border-[var(--brand-primary)] cursor-pointer">
                <svg class="w-4 h-4 text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Voltar para Minha Biblioteca</span>
            </a>

            <div class="flex items-center justify-end gap-3">
                @if ($inUserLibrary)
                <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-xs font-semibold font-['Open_Sans'] backdrop-blur-md shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Na sua biblioteca</span>
                </div>
                @endif

                <a
                    href="{{ route('games.edit', $game->slug) }}"
                    wire:navigate
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/40 text-amber-300 text-xs font-semibold font-['Open_Sans'] backdrop-blur-md shadow-sm transition-all hover:scale-105 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Editar Jogo</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Container Overlapping Hero -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-28 sm:-mt-36 md:-mt-44 relative z-10">
        <!-- Flash Status Message -->
        @if (session('status'))
        <div class="mb-6 p-4 rounded-[var(--radius-md)] bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-sm flex items-center justify-between shadow-lg backdrop-blur-sm animate-fade-in font-['Roboto']">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400/80 hover:text-emerald-400 text-xs font-bold px-2 py-1 cursor-pointer">
                ✕
            </button>
        </div>
        @endif

        <div class="flex flex-col md:flex-row gap-8 lg:gap-10">
            <!-- Left Column: Portrait Cover & Quick Access Info -->
            <div class="w-full max-w-[280px] sm:max-w-[320px] md:w-72 lg:w-80 mx-auto md:mx-0 shrink-0 space-y-6">
                <!-- Cover Image Card (Strict 2:3 Aspect Ratio) -->
                <div class="relative w-full aspect-[2/3] rounded-[var(--radius-lg)] overflow-hidden border border-[var(--border-color)] bg-black/50 shadow-2xl group">
                    <img
                        src="{{ $coverUrl }}"
                        alt="{{ $game->title }}"
                        onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80';"
                        class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105" />

                    <!-- Static Gradient Overlay for Smooth Transition -->
                    <div class="absolute inset-0 z-10 bg-gradient-to-t from-[var(--bg-card)] via-transparent to-black/40 pointer-events-none"></div>

                    <!-- Top Status Badge Overlay (if registered) -->
                    @if ($inUserLibrary)
                    <div class="absolute top-3 right-3 z-20 drop-shadow-md">
                        <x-game.status-badge :status="$status" size="md" />
                    </div>
                    @endif

                    <!-- Release Year Chip Top Left -->
                    @if ($game->release_year)
                    <div class="absolute top-3 left-3 z-20">
                        <span class="px-2.5 py-1 text-xs font-bold bg-black/75 text-white/90 rounded-[var(--radius-sm)] backdrop-blur-sm border border-white/10">
                            {{ $game->release_year }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Platforms Card -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] p-4 sm:p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Plataformas Disponíveis</span>
                    </h3>

                    @if ($game->platforms->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        @foreach ($game->platforms as $plat)
                        @php
                        $pColor = $platformColor($plat->name, $plat->slug);
                        @endphp
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-[var(--radius-sm)] text-xs font-semibold font-['Open_Sans'] shadow-sm border"
                            style="background-color: {{ $pColor['bg'] }}; color: {{ $pColor['text'] }}; border-color: {{ $pColor['border'] }};">
                            <span>{{ $plat->name }}</span>
                        </span>
                        @endforeach
                    </div>
                    @else
                    <p class="text-xs text-[var(--text-muted)] font-['Roboto']">Nenhuma plataforma informada.</p>
                    @endif
                </div>

                <!-- Digital Libraries Card -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] p-4 sm:p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-[var(--brand-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Bibliotecas Digitais</span>
                    </h3>

                    @if ($game->libraries->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        @foreach ($game->libraries as $lib)
                        @php
                        $lColor = $libraryColor($lib->name, $lib->slug);
                        @endphp
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-[var(--radius-sm)] text-xs font-semibold font-['Open_Sans'] shadow-sm border"
                            style="background-color: {{ $lColor['bg'] }}; color: {{ $lColor['text'] }}; border-color: {{ $lColor['border'] }};">
                            <span>{{ $lib->name }}</span>
                        </span>
                        @endforeach
                    </div>
                    @else
                    <p class="text-xs text-[var(--text-muted)] font-['Roboto']">Nenhuma biblioteca vinculada.</p>
                    @endif
                </div>

                <!-- Purchase Links Card -->
                @if (!empty($game->purchase_links) && is_array($game->purchase_links))
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] p-4 sm:p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Onde Comprar</span>
                    </h3>

                    <div class="flex flex-col gap-2">
                        @foreach ($game->purchase_links as $store => $url)
                        <a
                            href="{{ $url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex items-center justify-between px-3 py-2 rounded-[var(--radius-sm)] bg-[var(--bg-main)] hover:bg-[var(--border-color)]/30 border border-[var(--border-color)] text-xs font-medium text-[var(--text-main)] font-['Open_Sans'] transition-colors group">
                            <span class="capitalize font-semibold">{{ $store }}</span>
                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)] group-hover:text-[var(--brand-primary)] group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Game Details, Synopsis, Trailer & User Experience -->
            <div class="flex-1 min-w-0 space-y-8">
                <!-- Header Info Area -->
                <div class="space-y-4">
                    <!-- Title & Franchise Pill (High Contrast) -->
                    <div class="space-y-2">
                        @if ($game->is_franchise && $game->franchise_name)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-400/15 text-amber-300 border border-amber-400/30 font-['Open_Sans'] shadow-sm">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span>Franquia {{ $game->franchise_name }}</span>
                        </div>
                        @endif

                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold font-['Ubuntu'] text-[var(--text-main)] tracking-tight leading-tight">
                            {{ $game->title }}
                        </h1>
                    </div>

                    <!-- Metadata Grid / Pills -->
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs sm:text-sm text-[var(--text-muted)] font-['Roboto'] pt-1">
                        @if ($game->developer)
                        <div>
                            <span class="text-[var(--text-muted)]/70">Desenvolvedora:</span>
                            <strong class="text-[var(--text-main)] ml-1 font-medium">{{ $game->developer }}</strong>
                        </div>
                        @endif

                        @if ($game->publisher)
                        <div>
                            <span class="text-[var(--text-muted)]/70">Publicadora:</span>
                            <strong class="text-[var(--text-main)] ml-1 font-medium">{{ $game->publisher }}</strong>
                        </div>
                        @endif

                        @if ($game->release_year)
                        <div>
                            <span class="text-[var(--text-muted)]/70">Ano:</span>
                            <strong class="text-[var(--text-main)] ml-1 font-medium">{{ $game->release_year }}</strong>
                        </div>
                        @endif

                        <!-- Official Brazilian Age Rating Badge (ClassInd) -->
                        @if ($ratingData)
                        <div class="flex items-center gap-1.5">
                            <span class="text-[var(--text-muted)]/70">Classificação:</span>
                            <span
                                class="px-2.5 py-0.5 text-xs font-bold rounded-[var(--radius-sm)] shadow-sm font-['Ubuntu'] inline-flex items-center justify-center min-w-[28px] border border-black/20"
                                style="background-color: {{ $ratingData['bg'] }}; color: {{ $ratingData['text'] }};"
                                title="Classificação Indicativa: {{ $ratingData['label'] }}">
                                {{ $ratingData['label'] }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Genres Chips -->
                    @if (!empty($game->genre) && is_array($game->genre))
                    <div class="flex flex-wrap gap-2 pt-2">
                        @foreach ($game->genre as $genreName)
                        <span class="px-3 py-1 rounded-[var(--radius-sm)] text-xs font-medium bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] font-['Open_Sans'] shadow-sm">
                            {{ $genreName }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Synopsis Section -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 sm:p-7 shadow-sm space-y-3">
                    <h2 class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2">
                        <svg class="w-4 h-4 text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <span>Sinopse & Resumo</span>
                    </h2>

                    <p class="text-sm sm:text-base leading-relaxed text-[var(--text-main)]/90 font-['Roboto'] whitespace-pre-line">
                        {{ $game->synopsis ?: 'Nenhuma sinopse disponível para este jogo.' }}
                    </p>
                </div>

                <!-- Trailer Section (if available) -->
                @if ($youtubeEmbedUrl)
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 sm:p-7 shadow-sm space-y-4">
                    <h2 class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Trailer Oficial</span>
                    </h2>

                    <div class="relative w-full aspect-video rounded-[var(--radius-md)] overflow-hidden border border-[var(--border-color)] bg-black shadow-lg">
                        <iframe
                            src="{{ $youtubeEmbedUrl }}"
                            title="Trailer oficial de {{ $game->title }}"
                            class="w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                @endif

                <!-- User Personal Experience & Evaluation Section -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 sm:p-7 space-y-6 shadow-sm">
                    <div class="flex items-center gap-2.5 border-b border-[var(--border-color)] pb-4">
                        <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/15 text-[var(--brand-primary)]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)]">
                                Minha Experiência com o Jogo
                            </h2>
                            <p class="text-xs text-[var(--text-muted)] font-['Roboto']">
                                {{ $inUserLibrary ? 'Gerencie seu status, tempo de jogo, nota e suas impressões pessoais.' : 'Adicione este jogo à sua biblioteca pessoal registrando seu progresso.' }}
                            </p>
                        </div>
                    </div>

                    <form wire:submit.prevent="saveUserProgress" class="space-y-6">
                        <!-- Status Selection (Semantic Badges) -->
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2.5">
                                Status de Progresso <span class="text-red-400">*</span>
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                                @foreach ($statuses as $st)
                                @php
                                $isSelected = ($status === $st->value);
                                @endphp
                                <label
                                    wire:key="status-choice-{{ $st->value }}"
                                    class="relative flex items-center justify-center p-3 rounded-[var(--radius-md)] border text-xs font-semibold font-['Open_Sans'] cursor-pointer transition-all {{ $isSelected ? 'text-white shadow-md' : 'bg-[var(--bg-main)] text-[var(--text-muted)] border-[var(--border-color)] hover:border-[var(--brand-primary)]' }}"
                                    style="{{ $isSelected ? 'background-color: ' . $st->cssVariable() . '; border-color: ' . $st->cssVariable() . ';' : '' }}">
                                    <input
                                        type="radio"
                                        name="status"
                                        value="{{ $st->value }}"
                                        wire:model.live="status"
                                        class="sr-only" />
                                    <span>{{ $st->label() }}</span>
                                    @if ($isSelected)
                                    <svg class="w-4 h-4 ml-1.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    @endif
                                </label>
                                @endforeach
                            </div>
                            @error('status')
                            <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Hours Played & Rating Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Hours Played -->
                            <div>
                                <label for="hours_played" class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                                    Horas Jogadas
                                </label>
                                <div class="relative">
                                    <input
                                        type="number"
                                        step="0.1"
                                        min="0"
                                        id="hours_played"
                                        wire:model="hours_played"
                                        placeholder="0.0"
                                        class="w-full pl-3.5 pr-12 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto']" />
                                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-xs text-[var(--text-muted)] font-['Roboto']">
                                        horas
                                    </div>
                                </div>
                                @error('hours_played')
                                <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Star Rating (0 to 5) Interactive with Half-Stars and Hover Lighting -->
                            <div
                                x-data="{
                                    hoverRating: null,
                                    savedRating: @entangle('rating').live,
                                    get current() {
                                        return this.hoverRating !== null ? this.hoverRating : (this.savedRating ? Number(this.savedRating) : 0);
                                    }
                                }">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider">
                                        Avaliação (0 a 5 Estrelas)
                                    </label>
                                    <span
                                        x-show="current > 0"
                                        x-text="Number(current).toFixed(1) + ' / 5.0'"
                                        class="text-xs font-bold text-[var(--star-color)] font-['Ubuntu']"
                                        style="{{ ($rating !== null && $rating > 0) ? '' : 'display: none;' }}">
                                        {{ $rating ? number_format((float) $rating, 1) . ' / 5.0' : '' }}
                                    </span>
                                </div>

                                <!-- Compact Container Matching Input Height (h-[44px]) -->
                                <div
                                    @mouseleave="hoverRating = null"
                                    class="inline-flex items-center gap-2 px-3 py-2 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] h-[44px] shadow-xs">
                                    <div class="flex items-center gap-0.5">
                                        @for ($star = 1; $star <= 5; $star++)
                                            <div
                                            wire:key="interactive-detail-star-{{ $star }}"
                                            class="relative w-6 h-6 flex items-center justify-center select-none">
                                            <!-- Base Gray/Empty Star -->
                                            <svg class="w-5 h-5 text-[var(--border-color)] fill-current transition-colors" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>

                                            <!-- Full Star Layer (when current >= star) -->
                                            <div
                                                x-show="current >= {{ $star }}"
                                                class="absolute inset-0 flex items-center justify-center pointer-events-none transition-opacity duration-150">
                                                <svg class="w-5 h-5 text-[var(--star-color)] fill-current drop-shadow-[0_0_4px_rgba(255,184,0,0.5)]" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </div>

                                            <!-- Half Star Layer (when current === star - 0.5) -->
                                            <div
                                                x-show="current === {{ $star - 0.5 }}"
                                                class="absolute inset-0 w-1/2 overflow-hidden pointer-events-none flex items-center">
                                                <svg class="w-5 h-5 text-[var(--star-color)] fill-current drop-shadow-[0_0_4px_rgba(255,184,0,0.5)] max-w-none shrink-0" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </div>

                                            <!-- Left half hover & click (0.5 value) -->
                                            <button
                                                type="button"
                                                @mouseenter="hoverRating = {{ $star - 0.5 }}"
                                                wire:click="setRating({{ $star - 0.5 }})"
                                                class="absolute inset-y-0 left-0 w-1/2 cursor-pointer z-10 focus:outline-none"
                                                title="Nota {{ $star - 0.5 }}"></button>

                                            <!-- Right half hover & click (1.0 value) -->
                                            <button
                                                type="button"
                                                @mouseenter="hoverRating = {{ $star }}"
                                                wire:click="setRating({{ $star }})"
                                                class="absolute inset-y-0 right-0 w-1/2 cursor-pointer z-10 focus:outline-none"
                                                title="Nota {{ $star }}"></button>
                                    </div>
                                    @endfor
                                </div>

                                <!-- Clear Button -->
                                <button
                                    type="button"
                                    x-show="current > 0"
                                    wire:click="setRating(0)"
                                    @click="hoverRating = null"
                                    class="text-[11px] text-[var(--text-muted)] hover:text-red-400 font-['Roboto'] ml-1.5 px-1.5 py-0.5 rounded transition-colors cursor-pointer"
                                    style="{{ ($rating !== null && $rating > 0) ? '' : 'display: none;' }}">
                                    Limpar
                                </button>
                            </div>
                            @error('rating')
                            <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                            @enderror
                        </div>
                </div>

                <!-- Personal Review Textarea -->
                <div>
                    <label for="review" class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                        Review / Minhas Anotações Pessoais
                    </label>
                    <textarea
                        id="review"
                        wire:model="review"
                        rows="4"
                        placeholder="Escreva suas impressões, história, pontos positivos e negativos sobre o jogo..."
                        class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto'] resize-none"></textarea>
                    @error('review')
                    <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Actions Row at the Bottom: Remove Button on the left & Save Button on the right -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-[var(--border-color)]/60">
                    <div>
                        @if ($inUserLibrary)
                        <button
                            type="button"
                            wire:click="confirmRemoval"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-[var(--radius-md)] bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/30 text-xs font-semibold font-['Open_Sans'] cursor-pointer transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Remover da Biblioteca</span>
                        </button>
                        @endif
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-[var(--radius-md)] bg-[var(--brand-primary)] hover:brightness-110 text-white font-semibold text-sm font-['Open_Sans'] shadow-md cursor-pointer transition-all w-full sm:w-auto">
                        <svg wire:loading wire:target="saveUserProgress" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>{{ $inUserLibrary ? 'Salvar Alterações' : 'Salvar na Minha Biblioteca' }}</span>
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão Posicionado no Topo da Tela -->
@if ($showDeleteModal)
<div
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    @keydown.escape.window="$wire.cancelRemoval()">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/75 backdrop-blur-sm transition-opacity" wire:click="cancelRemoval"></div>

    <!-- Modal Card Posicionado no Topo -->
    <div class="relative min-h-screen flex items-start justify-center pt-8 sm:pt-14 px-4 pb-6">
        <div class="relative max-w-md w-full bg-[var(--bg-card)] border border-red-500/40 rounded-[var(--radius-lg)] p-6 shadow-2xl space-y-4 animate-in fade-in slide-in-from-top-4 duration-300">
            <!-- Modal Header com Ícone de Alerta -->
            <div class="flex items-start gap-3.5">
                <div class="p-2.5 rounded-full bg-red-500/15 border border-red-500/30 text-red-400 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)] leading-tight">
                        Remover da Biblioteca?
                    </h3>
                    <p class="text-xs text-[var(--text-muted)] font-['Roboto'] mt-1.5 leading-relaxed">
                        Tem certeza de que deseja remover <strong class="text-[var(--text-main)]">{{ $game->title }}</strong> da sua biblioteca? Seu histórico de horas jogadas, status e avaliações registradas serão excluídos.
                    </p>
                </div>
            </div>

            <!-- Modal Actions -->
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[var(--border-color)]/60">
                <button
                    type="button"
                    wire:click="cancelRemoval"
                    class="px-4 py-2 rounded-[var(--radius-md)] bg-[var(--bg-main)] hover:bg-[var(--border-color)]/30 border border-[var(--border-color)] text-xs font-semibold text-[var(--text-main)] font-['Open_Sans'] transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button
                    type="button"
                    wire:click="removeFromLibrary"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-[var(--radius-md)] bg-red-600 hover:bg-red-700 text-white text-xs font-semibold font-['Open_Sans'] shadow-md transition-colors cursor-pointer">
                    <svg wire:loading wire:target="removeFromLibrary" class="animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Sim, Remover</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
</div>