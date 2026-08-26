<div class="space-y-8 pb-12">
    <!-- Top Greeting & Quick Actions Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[var(--border-color)] pb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--text-main)] tracking-tight">
                Minha Biblioteca
            </h1>
            <p class="text-xs sm:text-sm text-[var(--text-muted)] font-['Roboto'] mt-1">
                Acompanhe o seu progresso, avaliações e catálogo unificado de jogos.
            </p>
        </div>

        <!-- Scope Switcher Tabs (My Library vs All Games) -->
        <div class="inline-flex p-1 bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] shadow-sm">
            <button
                type="button"
                wire:click="$set('scope', 'my_library')"
                class="px-3.5 py-1.5 text-xs font-semibold rounded-[var(--radius-sm)] transition-all font-['Open_Sans'] {{ $scope === 'my_library' ? 'bg-[var(--brand-primary)] text-white shadow-sm' : 'text-[var(--text-muted)] hover:text-[var(--text-main)]' }}"
            >
                Meus Jogos ({{ $stats['total_games'] }})
            </button>
            <button
                type="button"
                wire:click="$set('scope', 'all_games')"
                class="px-3.5 py-1.5 text-xs font-semibold rounded-[var(--radius-sm)] transition-all font-['Open_Sans'] {{ $scope === 'all_games' ? 'bg-[var(--brand-primary)] text-white shadow-sm' : 'text-[var(--text-muted)] hover:text-[var(--text-main)]' }}"
            >
                Explorar Catálogo
            </button>
        </div>
    </div>

    <!-- Feedback Toast -->
    @if ($feedbackMessage)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            class="flex items-center justify-between p-4 rounded-[var(--radius-md)] bg-[var(--status-finished)]/20 border border-[var(--status-finished)]/40 text-[var(--text-main)] text-sm font-['Roboto'] shadow-md"
        >
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-[var(--status-finished)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ $feedbackMessage }}</span>
            </div>
            <button @click="show = false" class="text-[var(--text-muted)] hover:text-[var(--text-main)]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Metrics & Statistics Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        <!-- Total Jogos -->
        <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] p-4 flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-[var(--brand-primary)]/50 hover:shadow-[var(--glow-retro)] group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider">Total Jogos</span>
                <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/15 text-[var(--brand-primary)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl font-bold font-['Ubuntu'] text-[var(--text-main)]">{{ $stats['total_games'] }}</span>
                <span class="text-[11px] text-[var(--text-muted)] font-['Roboto']">títulos</span>
            </div>
            <div class="w-full bg-[var(--border-color)] h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-[var(--brand-primary)] h-full w-full"></div>
            </div>
        </div>

        <!-- Finalizados -->
        <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] p-4 flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-[var(--status-finished)]/50 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider">Concluídos</span>
                <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--status-finished)]/15 text-[var(--status-finished)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-bold font-['Ubuntu'] text-[var(--text-main)]">{{ $stats['finished_count'] }}</span>
                    <span class="text-[11px] text-[var(--text-muted)] font-['Roboto']">jogos</span>
                </div>
                <span class="text-xs font-semibold text-[var(--status-finished)] font-['Roboto']">{{ $stats['finished_percentage'] }}%</span>
            </div>
            <div class="w-full bg-[var(--border-color)] h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-[var(--status-finished)] h-full transition-all duration-500" style="width: {{ $stats['finished_percentage'] }}%"></div>
            </div>
        </div>

        <!-- Em Andamento (Playing) -->
        <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] p-4 flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-[var(--status-playing)]/50 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider">Jogando</span>
                <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--status-playing)]/15 text-[var(--status-playing)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl font-bold font-['Ubuntu'] text-[var(--text-main)]">{{ $stats['playing_count'] }}</span>
                <span class="text-[11px] text-[var(--text-muted)] font-['Roboto']">em ação</span>
            </div>
            <div class="w-full bg-[var(--border-color)] h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-[var(--status-playing)] h-full" style="width: {{ $stats['total_games'] > 0 ? ($stats['playing_count'] / $stats['total_games']) * 100 : 0 }}%"></div>
            </div>
        </div>

        <!-- Backlog / Na Fila -->
        <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] p-4 flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-[var(--status-backlog)]/50 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider">Backlog</span>
                <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--status-backlog)]/15 text-[var(--status-backlog)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl font-bold font-['Ubuntu'] text-[var(--text-main)]">{{ $stats['backlog_count'] }}</span>
                <span class="text-[11px] text-[var(--text-muted)] font-['Roboto']">pendentes</span>
            </div>
            <div class="w-full bg-[var(--border-color)] h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-[var(--status-backlog)] h-full" style="width: {{ $stats['total_games'] > 0 ? ($stats['backlog_count'] / $stats['total_games']) * 100 : 0 }}%"></div>
            </div>
        </div>

        <!-- Horas Jogadas -->
        <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] p-4 flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-[var(--brand-secondary)]/50 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider">Horas Totais</span>
                <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--brand-secondary)]/15 text-[var(--brand-secondary)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl font-bold font-['Ubuntu'] text-[var(--text-main)]">{{ number_format($stats['total_hours_played'], 0, ',', '.') }}</span>
                <span class="text-[11px] text-[var(--text-muted)] font-['Roboto']">hrs</span>
            </div>
            <div class="w-full bg-[var(--border-color)] h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-[var(--brand-secondary)] h-full w-full"></div>
            </div>
        </div>

        <!-- Média de Avaliação -->
        <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] p-4 flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:border-[var(--star-color)]/50 group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider">Avaliação Média</span>
                <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--star-color)]/15 text-[var(--star-color)]">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-1.5">
                <span class="text-2xl font-bold font-['Ubuntu'] text-[var(--text-main)]">
                    {{ $stats['average_rating'] > 0 ? number_format($stats['average_rating'], 1) : '--' }}
                </span>
                <span class="text-[11px] text-[var(--text-muted)] font-['Roboto']">/ 5.0</span>
            </div>
            <div class="w-full bg-[var(--border-color)] h-1 rounded-full mt-3 overflow-hidden">
                <div class="bg-[var(--star-color)] h-full" style="width: {{ ($stats['average_rating'] / 5.0) * 100 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Advanced Filter & Search Panel -->
    <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-4 sm:p-5 space-y-4 shadow-sm">
        <!-- Main Search Row -->
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3">
            <!-- Search Input with Debounce -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[var(--text-muted)]">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por título do jogo, desenvolvedora, publicadora ou franquia..."
                    class="w-full pl-10 pr-10 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary)]/20 transition-all font-['Roboto']"
                />
                @if ($search !== '')
                    <button
                        type="button"
                        wire:click="$set('search', '')"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[var(--text-muted)] hover:text-[var(--text-main)]"
                        title="Limpar busca"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>

            <!-- Sort By Select & View Mode Toggle -->
            <div class="flex items-center gap-2 sm:gap-3">
                <select
                    wire:model.live="sortBy"
                    class="px-3 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs sm:text-sm text-[var(--text-main)] focus:outline-none focus:border-[var(--brand-primary)] font-['Open_Sans'] cursor-pointer"
                >
                    <option value="recent">Mais Recentes</option>
                    <option value="title_asc">Título (A - Z)</option>
                    <option value="title_desc">Título (Z - A)</option>
                    <option value="rating_desc">Maior Avaliação</option>
                    <option value="hours_desc">Mais Horas Jogadas</option>
                    <option value="year_desc">Ano (Mais Novo)</option>
                    <option value="year_asc">Ano (Mais Antigo)</option>
                </select>

                <!-- Grid vs List View -->
                <div class="inline-flex p-0.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)]">
                    <button
                        type="button"
                        wire:click="$set('viewMode', 'grid')"
                        class="p-2 rounded-[var(--radius-sm)] transition-all {{ $viewMode === 'grid' ? 'bg-[var(--brand-primary)] text-white' : 'text-[var(--text-muted)] hover:text-[var(--text-main)]' }}"
                        title="Visualização em Grid"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        wire:click="$set('viewMode', 'list')"
                        class="p-2 rounded-[var(--radius-sm)] transition-all {{ $viewMode === 'list' ? 'bg-[var(--brand-primary)] text-white' : 'text-[var(--text-muted)] hover:text-[var(--text-main)]' }}"
                        title="Visualização em Lista"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Dropdowns Row -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3 pt-3 border-t border-[var(--border-color)]/60">
            <!-- Plataforma -->
            <div>
                <label class="block text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider font-['Open_Sans'] mb-1">
                    Plataforma
                </label>
                <select
                    wire:model.live="platform"
                    class="w-full px-2.5 py-1.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs text-[var(--text-main)] focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto'] cursor-pointer"
                >
                    <option value="">Todas as Plataformas</option>
                    @foreach ($availablePlatforms as $plat)
                        <option value="{{ $plat['slug'] }}">{{ $plat['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Biblioteca Digital -->
            <div>
                <label class="block text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider font-['Open_Sans'] mb-1">
                    Biblioteca
                </label>
                <select
                    wire:model.live="library"
                    class="w-full px-2.5 py-1.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs text-[var(--text-main)] focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto'] cursor-pointer"
                >
                    <option value="">Todas as Bibliotecas</option>
                    @foreach ($availableLibraries as $lib)
                        <option value="{{ $lib['slug'] }}">{{ $lib['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status do Jogo -->
            <div>
                <label class="block text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider font-['Open_Sans'] mb-1">
                    Status
                </label>
                <select
                    wire:model.live="status"
                    class="w-full px-2.5 py-1.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs text-[var(--text-main)] focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto'] cursor-pointer"
                >
                    <option value="">Todos os Status</option>
                    @foreach ($statuses as $st)
                        <option value="{{ $st->value }}">{{ $st->label() }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Gênero -->
            <div>
                <label class="block text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider font-['Open_Sans'] mb-1">
                    Gênero
                </label>
                <select
                    wire:model.live="genre"
                    class="w-full px-2.5 py-1.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs text-[var(--text-main)] focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto'] cursor-pointer"
                >
                    <option value="">Todos os Gêneros</option>
                    @foreach ($availableGenres as $gen)
                        <option value="{{ $gen }}">{{ $gen }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Ano de Lançamento -->
            <div class="col-span-2 sm:col-span-1">
                <label class="block text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider font-['Open_Sans'] mb-1">
                    Ano
                </label>
                <input
                    type="number"
                    wire:model.live.debounce.300ms="year"
                    placeholder="Ex: 2023"
                    min="1970"
                    max="2035"
                    class="w-full px-2.5 py-1.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto']"
                />
            </div>
        </div>

        <!-- Active Filter Pills & Reset Button -->
        @if ($this->activeFiltersCount > 0)
            <div class="flex flex-wrap items-center justify-between gap-2 pt-2 text-xs font-['Roboto']">
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-[var(--text-muted)] font-medium">Filtros ativos ({{ $this->activeFiltersCount }}):</span>

                    @if ($search !== '')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/20 text-[var(--text-main)] border border-[var(--brand-primary)]/40">
                            Busca: "{{ Str::limit($search, 15) }}"
                            <button type="button" wire:click="$set('search', '')" class="hover:text-red-400">×</button>
                        </span>
                    @endif

                    @if ($platform !== '')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/20 text-[var(--text-main)] border border-[var(--brand-primary)]/40">
                            Plataforma: {{ $platform }}
                            <button type="button" wire:click="$set('platform', '')" class="hover:text-red-400">×</button>
                        </span>
                    @endif

                    @if ($library !== '')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/20 text-[var(--text-main)] border border-[var(--brand-primary)]/40">
                            Biblioteca: {{ $library }}
                            <button type="button" wire:click="$set('library', '')" class="hover:text-red-400">×</button>
                        </span>
                    @endif

                    @if ($status !== '')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/20 text-[var(--text-main)] border border-[var(--brand-primary)]/40">
                            Status: {{ $status }}
                            <button type="button" wire:click="$set('status', '')" class="hover:text-red-400">×</button>
                        </span>
                    @endif

                    @if ($genre !== '')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/20 text-[var(--text-main)] border border-[var(--brand-primary)]/40">
                            Gênero: {{ $genre }}
                            <button type="button" wire:click="$set('genre', '')" class="hover:text-red-400">×</button>
                        </span>
                    @endif

                    @if ($year !== '')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/20 text-[var(--text-main)] border border-[var(--brand-primary)]/40">
                            Ano: {{ $year }}
                            <button type="button" wire:click="$set('year', '')" class="hover:text-red-400">×</button>
                        </span>
                    @endif
                </div>

                <button
                    type="button"
                    wire:click="resetFilters"
                    class="text-xs font-semibold text-[var(--brand-secondary)] hover:underline flex items-center gap-1 font-['Open_Sans']"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Limpar Todos
                </button>
            </div>
        @endif
    </div>

    <!-- Games Presentation (Grid or List View) -->
    @if ($games->isEmpty())
        <!-- Empty State Component -->
        <div class="py-16 px-4 text-center rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] border-dashed space-y-4">
            <div class="w-16 h-16 mx-auto rounded-full bg-[var(--brand-primary)]/10 text-[var(--brand-primary)] flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                </svg>
            </div>
            <div class="max-w-md mx-auto space-y-1">
                <h3 class="text-base sm:text-lg font-bold font-['Ubuntu'] text-[var(--text-main)]">
                    Nenhum jogo encontrado
                </h3>
                <p class="text-xs sm:text-sm text-[var(--text-muted)] font-['Roboto']">
                    Não encontramos nenhum jogo correspondente aos filtros aplicados. Tente ajustar os termos de busca ou limpar os filtros.
                </p>
            </div>
            <div>
                <button
                    type="button"
                    wire:click="resetFilters"
                    class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white hover:opacity-90 transition-all font-['Open_Sans'] shadow-md"
                >
                    Redefinir Filtros
                </button>
            </div>
        </div>
    @else
        <!-- Grid View (2:3 Aspect Ratio Covers) -->
        @if ($viewMode === 'grid')
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 sm:gap-5">
                @foreach ($games as $game)
                    @php
                        $userGame = $game->userGames->first();
                        $displayStatus = $userGame?->status;
                        $displayRating = $userGame?->rating ?? 0.0;
                        $displayHours = $userGame ? (float) $userGame->hours_played : 0.0;
                        $coverUrl = $game->cover_image ?? 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80';
                    @endphp

                    <div
                        wire:key="game-card-{{ $game->id }}"
                        class="group relative flex flex-col bg-[var(--bg-card)] rounded-[var(--radius-md)] overflow-hidden border border-[var(--border-color)] hover:border-[var(--brand-primary)]/50 hover:shadow-[var(--glow-retro)] transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <!-- Cover Image Container (Strict 2:3 Aspect Ratio) -->
                        <div class="relative w-full aspect-[2/3] overflow-hidden bg-black/40 cursor-pointer" wire:click="openEditModal({{ $game->id }})">
                            <img
                                src="{{ $coverUrl }}"
                                alt="{{ $game->title }}"
                                class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                                loading="lazy"
                            />

                            <!-- Gradient Overlay for Contrast -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg-card)] via-transparent to-black/50 opacity-90 group-hover:opacity-60 transition-opacity"></div>

                            <!-- Top Right Status Badge Overlay -->
                            @if ($displayStatus)
                                <div class="absolute top-2 right-2 z-10 drop-shadow-md">
                                    <x-game.status-badge :status="$displayStatus" size="sm" />
                                </div>
                            @endif

                            <!-- Release Year Chip Top Left -->
                            @if ($game->release_year)
                                <div class="absolute top-2 left-2 z-10">
                                    <span class="px-1.5 py-0.5 text-[10px] font-semibold bg-black/75 text-white/90 rounded-[var(--radius-sm)] backdrop-blur-sm">
                                        {{ $game->release_year }}
                                    </span>
                                </div>
                            @endif

                            <!-- Quick Action Overlay on Hover -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 backdrop-blur-[2px]">
                                <button
                                    type="button"
                                    wire:click.stop="openEditModal({{ $game->id }})"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-[var(--radius-sm)] bg-[var(--brand-primary)] text-white shadow-lg font-['Open_Sans'] transform translate-y-2 group-hover:translate-y-0 transition-transform"
                                >
                                    Editar Progresso
                                </button>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-3 flex flex-col justify-between flex-1 gap-2">
                            <div>
                                <h3
                                    wire:click="openEditModal({{ $game->id }})"
                                    class="font-bold text-xs sm:text-sm text-[var(--text-main)] font-['Ubuntu'] leading-snug line-clamp-1 group-hover:text-[var(--brand-primary)] cursor-pointer transition-colors"
                                    title="{{ $game->title }}"
                                >
                                    {{ $game->title }}
                                </h3>

                                @if ($game->developer)
                                    <p class="text-[11px] text-[var(--text-muted)] font-['Roboto'] line-clamp-1 mt-0.5">
                                        {{ $game->developer }}
                                    </p>
                                @endif
                            </div>

                            <!-- Footer with Rating & Platforms / Hours -->
                            <div class="flex items-center justify-between gap-1 pt-2 border-t border-[var(--border-color)]/60 text-[11px]">
                                <x-game.star-rating :rating="$displayRating" size="sm" :showNumber="true" />

                                @if ($displayHours > 0)
                                    <span class="text-[10px] font-medium text-[var(--text-muted)] font-['Roboto'] flex items-center gap-0.5" title="Horas jogadas">
                                        <svg class="w-3 h-3 text-[var(--brand-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ number_format($displayHours, 0) }}h
                                    </span>
                                @elseif ($game->relationLoaded('platforms') && $game->platforms->isNotEmpty())
                                    <span class="px-1.5 py-0.5 text-[9px] font-semibold bg-[var(--border-color)] text-[var(--text-muted)] rounded-[var(--radius-sm)]">
                                        {{ Str::limit($game->platforms->first()->name, 8, '') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- List / Table View -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] overflow-hidden divide-y divide-[var(--border-color)]">
                @foreach ($games as $game)
                    @php
                        $userGame = $game->userGames->first();
                        $displayStatus = $userGame?->status;
                        $displayRating = $userGame?->rating ?? 0.0;
                        $displayHours = $userGame ? (float) $userGame->hours_played : 0.0;
                        $coverUrl = $game->cover_image ?? 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80';
                    @endphp

                    <div
                        wire:key="game-list-{{ $game->id }}"
                        class="p-3 sm:p-4 flex items-center justify-between gap-4 hover:bg-[var(--border-color)]/20 transition-colors"
                    >
                        <!-- Cover Thumbnail & Title -->
                        <div class="flex items-center gap-3 sm:gap-4 min-w-0 flex-1 cursor-pointer" wire:click="openEditModal({{ $game->id }})">
                            <div class="w-12 h-16 sm:w-14 sm:h-20 aspect-[2/3] rounded-[var(--radius-sm)] overflow-hidden bg-black/40 flex-shrink-0">
                                <img src="{{ $coverUrl }}" alt="{{ $game->title }}" class="w-full h-full object-cover" loading="lazy" />
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-sm sm:text-base text-[var(--text-main)] font-['Ubuntu'] truncate">
                                    {{ $game->title }}
                                </h3>
                                <p class="text-xs text-[var(--text-muted)] font-['Roboto'] truncate">
                                    {{ $game->developer ?? 'Desenvolvedora não informada' }} • {{ $game->release_year ?? '--' }}
                                </p>
                                <!-- Platforms -->
                                @if ($game->platforms->isNotEmpty())
                                    <div class="flex items-center gap-1 mt-1 overflow-hidden">
                                        @foreach ($game->platforms->take(3) as $platform)
                                            <span class="px-1.5 py-0.2 text-[10px] font-medium bg-[var(--border-color)] text-[var(--text-muted)] rounded-[var(--radius-sm)]">
                                                {{ $platform->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Status, Rating & Actions -->
                        <div class="flex items-center gap-3 sm:gap-6 flex-shrink-0">
                            @if ($displayStatus)
                                <x-game.status-badge :status="$displayStatus" size="sm" />
                            @endif

                            <div class="hidden sm:flex flex-col items-end text-xs font-['Roboto']">
                                <x-game.star-rating :rating="$displayRating" size="sm" :showNumber="true" />
                                <span class="text-[11px] text-[var(--text-muted)] mt-0.5">
                                    {{ number_format($displayHours, 1) }} horas
                                </span>
                            </div>

                            <button
                                type="button"
                                wire:click="openEditModal({{ $game->id }})"
                                class="px-3 py-1.5 text-xs font-semibold rounded-[var(--radius-sm)] bg-[var(--brand-primary)] text-white hover:opacity-90 font-['Open_Sans'] shadow-sm"
                            >
                                Gerenciar
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Pagination Bar -->
        <div class="mt-6">
            {{ $games->links() }}
        </div>
    @endif

    <!-- Quick Edit Modal (In-place Game Progress Update) -->
    @if ($showEditModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm transition-opacity"
            x-data
            @keydown.escape.window="$wire.closeEditModal()"
        >
            <div
                class="relative w-full max-w-lg bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] shadow-[var(--elevation-high)] overflow-hidden animate-in fade-in zoom-in-95 duration-200"
                @click.away="$wire.closeEditModal()"
            >
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-[var(--border-color)] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/20 text-[var(--brand-primary)] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)]">
                                Atualizar Progresso
                            </h3>
                            <p class="text-xs text-[var(--text-muted)] font-['Roboto'] truncate max-w-xs sm:max-w-sm">
                                {{ $editingGameTitle }}
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="closeEditModal"
                        class="text-[var(--text-muted)] hover:text-[var(--text-main)] p-1 rounded-md transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Form Body -->
                <form wire:submit="saveGameProgress" class="p-6 space-y-4 font-['Roboto'] text-sm">
                    <!-- Status Selector -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider font-['Open_Sans'] mb-1.5">
                            Status Atual
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach ($statuses as $st)
                                <label
                                    class="flex items-center justify-center gap-1.5 p-2 rounded-[var(--radius-md)] border cursor-pointer text-xs font-semibold font-['Open_Sans'] transition-all {{ $editStatus === $st->value ? 'border-transparent text-white shadow-sm' : 'border-[var(--border-color)] bg-[var(--bg-main)] text-[var(--text-muted)] hover:text-[var(--text-main)]' }}"
                                    style="{{ $editStatus === $st->value ? 'background-color: ' . $st->cssVariable() : '' }}"
                                >
                                    <input
                                        type="radio"
                                        wire:model.live="editStatus"
                                        value="{{ $st->value }}"
                                        class="sr-only"
                                    />
                                    <span>{{ $st->label() }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Hours Played & Star Rating Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Hours Played -->
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider font-['Open_Sans'] mb-1.5">
                                Horas Jogadas
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    step="0.5"
                                    min="0"
                                    max="9999"
                                    wire:model="editHours"
                                    class="w-full pl-3 pr-10 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto']"
                                    placeholder="0.0"
                                />
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-[var(--text-muted)] font-medium">
                                    horas
                                </span>
                            </div>
                        </div>

                        <!-- Star Rating (0 to 5) -->
                        <div>
                            <label class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider font-['Open_Sans'] mb-1.5">
                                Avaliação (0 a 5 Estrelas)
                            </label>
                            <div class="flex items-center gap-1.5 py-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button
                                        type="button"
                                        wire:click="$set('editRating', {{ $i }})"
                                        class="p-1 text-[var(--border-color)] hover:text-[var(--star-color)] transition-colors focus:outline-none"
                                        title="{{ $i }} estrelas"
                                    >
                                        <svg
                                            class="w-6 h-6 fill-current {{ $editRating >= $i ? 'text-[var(--star-color)]' : 'text-[var(--border-color)]' }}"
                                            viewBox="0 0 20 20"
                                        >
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                @endfor
                                <span class="ml-2 text-xs font-bold text-[var(--text-main)] font-['Ubuntu']">
                                    {{ $editRating > 0 ? number_format($editRating, 1) : 'Sem nota' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Review Text -->
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider font-['Open_Sans'] mb-1.5">
                            Review / Anotações Pessoais
                        </label>
                        <textarea
                            wire:model="editReview"
                            rows="3"
                            placeholder="Escreva suas impressões, análises ou anotações sobre este jogo..."
                            class="w-full p-3 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto']"
                        ></textarea>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-4 border-t border-[var(--border-color)] flex items-center justify-end gap-3">
                        <button
                            type="button"
                            wire:click="closeEditModal"
                            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-[var(--radius-md)] border border-[var(--border-color)] text-[var(--text-muted)] hover:text-[var(--text-main)] font-['Open_Sans'] transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2 text-xs sm:text-sm font-semibold rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white hover:opacity-90 font-['Open_Sans'] shadow-md transition-all flex items-center gap-2"
                        >
                            <span wire:loading.remove wire:target="saveGameProgress">Salvar Alterações</span>
                            <span wire:loading wire:target="saveGameProgress">Salvando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
