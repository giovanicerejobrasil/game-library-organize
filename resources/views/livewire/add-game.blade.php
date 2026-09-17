<div class="space-y-8 mt-6 pb-20">
    <!-- Top Breadcrumbs & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[var(--border-color)] pb-6">
        <div>
            <div class="flex items-center gap-2 text-xs font-['Open_Sans'] text-[var(--text-muted)] mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-[var(--brand-primary)] flex items-center gap-1 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Minha Biblioteca</span>
                </a>
                <span>/</span>
                <span class="text-[var(--text-main)] font-semibold">Adicionar Jogo</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--text-main)] tracking-tight">
                Adicionar Jogo à Biblioteca
            </h1>
            <p class="text-xs sm:text-sm text-[var(--text-muted)] font-['Roboto'] mt-1.5">
                Cadastre um novo título ou importe do catálogo e registre seu progresso, horas e avaliação.
            </p>
        </div>

        <div>
            <a
                href="{{ route('dashboard') }}"
                class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] hover:border-[var(--brand-primary)] hover:shadow-[var(--glow-retro)] transition-all font-['Open_Sans'] flex items-center gap-1.5 cursor-pointer shadow-sm">
                <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Cancelar</span>
            </a>
        </div>
    </div>

    <!-- Quick Catalog Search & Autofill Banner -->
    <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-5 sm:p-6 relative shadow-sm space-y-3">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="space-y-1">
                <h2 class="text-sm font-bold font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2">
                    <svg class="w-4.5 h-4.5 text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Importar jogo já cadastrado no catálogo (opcional)
                </h2>
                <p class="text-xs text-[var(--text-muted)] font-['Roboto']">
                    Se o jogo já existir no sistema, selecione-o para preencher os dados e configurar suas horas e avaliações.
                </p>
            </div>

            @if ($existing_game_id)
            <div class="flex items-center gap-2.5 bg-[var(--brand-primary)]/15 border border-[var(--brand-primary)]/40 px-3.5 py-2 rounded-[var(--radius-md)] text-xs text-[var(--text-main)] shadow-sm">
                <span>Vinculado a: <strong>{{ $selectedGameTitle }}</strong></span>
                <button
                    type="button"
                    wire:click="clearCatalogSelection"
                    class="text-[var(--brand-secondary)] hover:underline ml-2 font-bold cursor-pointer transition-colors">
                    ✕ Desvincular e Limpar
                </button>
            </div>
            @endif
        </div>

        @if (! $existing_game_id)
        <div class="mt-4 relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[var(--text-muted)] z-10">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <input
                type="text"
                wire:model.live.debounce.300ms="catalogSearch"
                placeholder="Digite o título do jogo para buscar no catálogo..."
                style="padding-left: 2.75rem;"
                class="w-full pr-4 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs sm:text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] font-['Roboto'] transition-all" />

            <!-- Autocomplete Suggestions Dropdown -->
            @if ($catalogResults->isNotEmpty())
            <div class="absolute left-0 right-0 mt-1.5 bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] shadow-[var(--elevation-high)] z-30 divide-y divide-[var(--border-color)] overflow-hidden max-h-64 overflow-y-auto">
                @foreach ($catalogResults as $catalogGame)
                <button
                    type="button"
                    wire:key="catalog-result-{{ $catalogGame->id }}"
                    wire:click="selectCatalogGame({{ $catalogGame->id }})"
                    class="w-full text-left px-4 py-3 hover:bg-[var(--border-color)]/30 transition-colors flex items-center justify-between gap-3 cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-12 aspect-[2/3] rounded bg-[var(--bg-main)] overflow-hidden flex-shrink-0 border border-[var(--border-color)]">
                            @if ($catalogGame->cover_image)
                            <img src="{{ $catalogGame->cover_image }}" alt="{{ $catalogGame->title }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-xs sm:text-sm text-[var(--text-main)] font-['Ubuntu']">{{ $catalogGame->title }}</p>
                            <p class="text-xs text-[var(--text-muted)] font-['Roboto'] mt-0.5">
                                {{ $catalogGame->developer ?? 'Dev n/d' }} • {{ $catalogGame->release_year ?? '--' }}
                            </p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-[var(--brand-primary)] font-['Open_Sans']">
                        Selecionar →
                    </span>
                </button>
                @endforeach
            </div>
            @elseif ($catalogSearch !== '')
            <div class="absolute left-0 right-0 mt-1.5 bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-md)] p-3 text-xs text-[var(--text-muted)] z-30 shadow-md">
                Nenhum jogo encontrado com esse termo. Você pode preencher o formulário abaixo para cadastrar do zero.
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Main Form & Live Preview Grid -->
    <form wire:submit.prevent="save" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Left Side: Form Sections (8 Cols) -->
        <div class="lg:col-span-8 space-y-8">

            <!-- Card 1: Informações Principais do Jogo -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 sm:p-7 space-y-6 shadow-sm">
                <div class="flex items-center gap-2.5 border-b border-[var(--border-color)] pb-4">
                    <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/15 text-[var(--brand-primary)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <h2 class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)]">
                        Informações Globais do Jogo
                    </h2>
                </div>

                <div class="space-y-5">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                            Título do Jogo <span class="text-[var(--brand-secondary)]">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            wire:model.live="title"
                            placeholder="Ex: The Legend of Zelda: Tears of the Kingdom"
                            class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] font-['Roboto'] transition-all" />
                        @error('title')
                        <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Year, Developer, Publisher Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="release_year" class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                                Ano de Lançamento
                            </label>
                            <input
                                type="number"
                                id="release_year"
                                wire:model.live="release_year"
                                placeholder="Ex: 2023"
                                min="1970"
                                max="2035"
                                class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] font-['Roboto'] transition-all" />
                            @error('release_year')
                            <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="developer" class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                                Desenvolvedora
                            </label>
                            <input
                                type="text"
                                id="developer"
                                wire:model.live="developer"
                                placeholder="Ex: Nintendo EPD"
                                class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] font-['Roboto'] transition-all" />
                            @error('developer')
                            <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="publisher" class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                                Publicadora
                            </label>
                            <input
                                type="text"
                                id="publisher"
                                wire:model.live="publisher"
                                placeholder="Ex: Nintendo"
                                class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] font-['Roboto'] transition-all" />
                            @error('publisher')
                            <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Synopsis (Large, Non-resizable) -->
                    <div>
                        <label for="synopsis" class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                            Sinopse / Resumo
                        </label>
                        <textarea
                            id="synopsis"
                            rows="6"
                            wire:model.live="synopsis"
                            placeholder="Descreva uma breve sinopse do jogo..."
                            class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] font-['Roboto'] resize-none leading-relaxed transition-all"></textarea>
                        @error('synopsis')
                        <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Card 2: Mídia Visual & Vídeo do Trailer -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 sm:p-7 space-y-6 shadow-sm">
                <div class="flex items-center gap-2.5 border-b border-[var(--border-color)] pb-4">
                    <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/15 text-[var(--brand-primary)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <h2 class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)]">
                        Mídia Visual & Trailer
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Cover Image URL & File Upload -->
                    <div class="space-y-3">
                        <label class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                            Arte de Capa (Proporção 2:3)
                        </label>
                        <input
                            type="url"
                            wire:model.live="cover_image"
                            placeholder="URL da imagem (ex: https://...)"
                            class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs sm:text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto']" />

                        <div class="pt-1">
                            <span class="text-xs text-[var(--text-muted)] block mb-1.5 font-['Roboto']">ou envie um arquivo local:</span>
                            <input
                                type="file"
                                wire:model="cover_file"
                                accept="image/*"
                                class="block w-full text-xs text-[var(--text-muted)] file:mr-3 file:py-1.5 file:px-3 file:rounded-[var(--radius-sm)] file:border-0 file:text-xs file:font-semibold file:bg-[var(--brand-primary)] file:text-white hover:file:opacity-90 cursor-pointer" />
                        </div>

                        @error('cover_image')
                        <span class="text-xs text-red-500 block font-['Roboto']">{{ $message }}</span>
                        @enderror
                        @error('cover_file')
                        <span class="text-xs text-red-500 block font-['Roboto']">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Background Image URL & File Upload -->
                    <div class="space-y-3">
                        <label class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                            Imagem de Fundo / Background (16:9)
                        </label>
                        <input
                            type="url"
                            wire:model.live="background_image"
                            placeholder="URL do background (ex: https://...)"
                            class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs sm:text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto']" />

                        <div class="pt-1">
                            <span class="text-xs text-[var(--text-muted)] block mb-1.5 font-['Roboto']">ou envie um arquivo local:</span>
                            <input
                                type="file"
                                wire:model="background_file"
                                accept="image/*"
                                class="block w-full text-xs text-[var(--text-muted)] file:mr-3 file:py-1.5 file:px-3 file:rounded-[var(--radius-sm)] file:border-0 file:text-xs file:font-semibold file:bg-[var(--brand-primary)] file:text-white hover:file:opacity-90 cursor-pointer" />
                        </div>

                        @error('background_image')
                        <span class="text-xs text-red-500 block font-['Roboto']">{{ $message }}</span>
                        @enderror
                        @error('background_file')
                        <span class="text-xs text-red-500 block font-['Roboto']">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Trailer URL -->
                <div class="pt-1">
                    <label for="trailer_url" class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                        Trailer Oficial (Link do YouTube)
                    </label>
                    <input
                        type="url"
                        id="trailer_url"
                        wire:model.live="trailer_url"
                        placeholder="Ex: https://www.youtube.com/watch?v=..."
                        class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto']" />
                    @error('trailer_url')
                    <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Card 3: Plataformas & Bibliotecas Digitais -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 sm:p-7 space-y-6 shadow-sm">
                <div class="flex items-center gap-2.5 border-b border-[var(--border-color)] pb-4">
                    <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/15 text-[var(--brand-primary)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <h2 class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)]">
                        Plataformas & Bibliotecas Onde Você Possui o Jogo
                    </h2>
                </div>

                <!-- Platforms Selection -->
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2.5">
                        Plataformas
                    </label>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach ($availablePlatforms as $plat)
                        <label
                            wire:key="platform-chip-{{ $plat->id }}"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-[var(--radius-md)] border text-xs font-['Open_Sans'] cursor-pointer transition-all {{ in_array($plat->id, $selected_platforms) ? 'bg-[var(--brand-primary)] text-white border-[var(--brand-primary)] shadow-sm' : 'bg-[var(--bg-main)] text-[var(--text-main)] border-[var(--border-color)] hover:border-[var(--brand-primary)]' }}">
                            <input
                                type="checkbox"
                                value="{{ $plat->id }}"
                                wire:model.live="selected_platforms"
                                class="sr-only" />
                            <span>{{ $plat->name }}</span>
                            @if (in_array($plat->id, $selected_platforms))
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            @endif
                        </label>
                        @endforeach
                    </div>
                    @error('selected_platforms')
                    <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Libraries Selection -->
                <div class="pt-2">
                    <label class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2.5">
                        Bibliotecas Digitais
                    </label>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach ($availableLibraries as $lib)
                        <label
                            wire:key="library-chip-{{ $lib->id }}"
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-[var(--radius-md)] border text-xs font-['Open_Sans'] cursor-pointer transition-all {{ in_array($lib->id, $selected_libraries) ? 'bg-[var(--brand-secondary)] text-white border-[var(--brand-secondary)] shadow-sm' : 'bg-[var(--bg-main)] text-[var(--text-main)] border-[var(--border-color)] hover:border-[var(--brand-secondary)]' }}">
                            <input
                                type="checkbox"
                                value="{{ $lib->id }}"
                                wire:model.live="selected_libraries"
                                class="sr-only" />
                            <span>{{ $lib->name }}</span>
                            @if (in_array($lib->id, $selected_libraries))
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            @endif
                        </label>
                        @endforeach
                    </div>
                    @error('selected_libraries')
                    <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Card 4: Gêneros, Franquia, Classificação & Lojas -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 sm:p-7 space-y-6 shadow-sm">
                <div class="flex items-center gap-2.5 border-b border-[var(--border-color)] pb-4">
                    <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/15 text-[var(--brand-primary)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </span>
                    <h2 class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)]">
                        Gêneros, Franquia & Classificação
                    </h2>
                </div>

                <!-- Genres -->
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2.5">
                        Gêneros
                    </label>
                    <div class="flex flex-wrap gap-2 mb-3.5">
                        @foreach ($availableGenres as $genreOption)
                        <button
                            type="button"
                            wire:key="genre-chip-{{ $genreOption }}"
                            wire:click="toggleGenre('{{ $genreOption }}')"
                            class="px-3 py-1.5 rounded-[var(--radius-sm)] text-xs font-['Roboto'] cursor-pointer transition-all {{ in_array($genreOption, $selected_genres) ? 'bg-[var(--brand-primary)] text-white font-semibold' : 'bg-[var(--bg-main)] text-[var(--text-muted)] border border-[var(--border-color)] hover:text-[var(--text-main)]' }}">
                            {{ $genreOption }}
                        </button>
                        @endforeach
                    </div>

                    <!-- Add custom genre -->
                    <div class="flex items-center gap-2 max-w-sm">
                        <input
                            type="text"
                            wire:model="custom_genre"
                            placeholder="Outro gênero..."
                            class="flex-1 px-3 py-1.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto']" />
                        <button
                            type="button"
                            wire:click="addCustomGenre"
                            class="px-3 py-1.5 bg-[var(--bg-card)] border border-[var(--border-color)] hover:border-[var(--brand-primary)] rounded-[var(--radius-md)] text-xs font-semibold text-[var(--text-main)] font-['Open_Sans'] cursor-pointer">
                            + Adicionar
                        </button>
                    </div>
                </div>

                <!-- Franchise & Age Rating Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                    <!-- Franchise -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2.5 text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model.live="is_franchise"
                                class="rounded border-[var(--border-color)] text-[var(--brand-primary)] focus:ring-0 w-4 h-4" />
                            <span>Faz parte de uma Franquia?</span>
                        </label>

                        @if ($is_franchise)
                        <input
                            type="text"
                            wire:model.live="franchise_name"
                            placeholder="Nome da franquia (ex: The Legend of Zelda)"
                            class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs sm:text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto']" />
                        @endif
                    </div>

                    <!-- Age Rating -->
                    <div>
                        <label for="age_rating" class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                            Classificação Indicativa
                        </label>
                        <select
                            id="age_rating"
                            wire:model.live="age_rating"
                            class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs sm:text-sm text-[var(--text-main)] focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto'] cursor-pointer">
                            <option value="">Não informada</option>
                            <option value="Livre">Livre (Todos os públicos)</option>
                            <option value="10+">10+ (Não recomendado para menores de 10 anos)</option>
                            <option value="12+">12+ (Não recomendado para menores de 12 anos)</option>
                            <option value="14+">14+ (Não recomendado para menores de 14 anos)</option>
                            <option value="16+">16+ (Não recomendado para menores de 16 anos)</option>
                            <option value="18+">18+ (Não recomendado para menores de 18 anos)</option>
                        </select>
                    </div>
                </div>

                <!-- Purchase Links -->
                <div class="pt-2">
                    <div class="flex items-center justify-between mb-2.5">
                        <label class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider">
                            Links de Páginas de Compra / Lojas
                        </label>
                        <button
                            type="button"
                            wire:click="addPurchaseLink"
                            class="text-xs font-semibold text-[var(--brand-primary)] hover:underline flex items-center gap-1 cursor-pointer font-['Open_Sans']">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Adicionar Loja
                        </button>
                    </div>

                    <div class="space-y-2.5">
                        @foreach ($purchase_links as $index => $link)
                        <div wire:key="purchase-link-{{ $index }}" class="flex items-center gap-2.5">
                            <input
                                type="text"
                                wire:model="purchase_links.{{ $index }}.store"
                                placeholder="Loja (ex: Steam, Epic)"
                                class="w-1/3 px-3.5 py-2 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs text-[var(--text-main)] focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto']" />
                            <input
                                type="url"
                                wire:model="purchase_links.{{ $index }}.url"
                                placeholder="https://store.steampowered.com/app/..."
                                class="flex-1 px-3.5 py-2 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-xs text-[var(--text-main)] focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto']" />
                            <button
                                type="button"
                                wire:click="removePurchaseLink({{ $index }})"
                                class="p-2 text-red-400 hover:text-red-500 cursor-pointer transition-colors"
                                title="Remover link">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @if ($existing_game_id)
            {{-- Card 5: Minha Experiência (Exibido APENAS se o usuário selecionar um jogo já existente) --}}
            <div class="bg-[var(--bg-card)] border-2 border-[var(--brand-primary)]/40 rounded-[var(--radius-lg)] p-6 sm:p-7 space-y-6 shadow-md">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4">
                    <div class="flex items-center gap-2.5">
                        <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)] text-white shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)]">
                                Minha Experiência (Progresso & Avaliação)
                            </h2>
                            <p class="text-xs text-[var(--text-muted)] font-['Roboto'] mt-0.5">
                                Registre seu status, horas e review para <strong>{{ $selectedGameTitle }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Status Selector with Semantic Colors -->
                <div>
                    <label class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2.5">
                        Status na Sua Biblioteca <span class="text-[var(--brand-secondary)]">*</span>
                    </label>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach ($statuses as $st)
                        <button
                            type="button"
                            wire:key="status-button-{{ $st->value }}"
                            wire:click="$set('status', '{{ $st->value }}')"
                            class="p-3 rounded-[var(--radius-md)] border flex flex-col items-center justify-center gap-1.5 transition-all cursor-pointer {{ $status === $st->value ? 'text-white shadow-md font-semibold scale-[1.02]' : 'bg-[var(--bg-main)] text-[var(--text-muted)] border-[var(--border-color)] hover:text-[var(--text-main)] hover:border-[var(--brand-primary)]/40' }}"
                            style="{{ $status === $st->value ? 'background-color: ' . $st->cssVariable() . '; border-color: ' . $st->cssVariable() . ';' : '' }}">
                            <span class="text-xs font-['Open_Sans']">{{ $st->label() }}</span>
                        </button>
                        @endforeach
                    </div>
                    @error('status')
                    <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hours Played & Star Rating Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Hours Played -->
                    <div>
                        <label for="hours_played" class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                            Tempo de Jogo (em horas)
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                step="0.1"
                                min="0"
                                id="hours_played"
                                wire:model.live="hours_played"
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

                    <!-- Star Rating (0 to 5) Interactive -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider">
                                Avaliação (Nota 0 a 5)
                            </label>
                            @if ($rating !== null && $rating > 0)
                            <span class="text-xs font-bold text-[var(--star-color)] font-['Ubuntu']">
                                {{ number_format((float) $rating, 1) }} / 5.0
                            </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-1.5 p-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)]">
                            @for ($star = 1; $star <= 5; $star++)
                                <button
                                type="button"
                                wire:key="star-btn-{{ $star }}"
                                wire:click="setRating({{ $star }})"
                                class="p-1 text-[var(--star-color)] hover:scale-125 transition-transform cursor-pointer focus:outline-none"
                                title="Nota {{ $star }}">
                                <svg class="w-6 h-6 {{ ($rating !== null && (float) $rating >= $star) ? 'fill-current' : 'text-[var(--border-color)] fill-current hover:text-[var(--star-color)]/60' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                </button>
                                @endfor

                                @if ($rating !== null)
                                <button
                                    type="button"
                                    wire:click="$set('rating', null)"
                                    class="text-[11px] text-[var(--text-muted)] hover:text-[var(--brand-secondary)] ml-auto font-['Roboto'] cursor-pointer">
                                    Limpar
                                </button>
                                @endif
                        </div>
                        @error('rating')
                        <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Written Review (Non-resizable) -->
                <div>
                    <label for="review" class="block text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] uppercase tracking-wider mb-2">
                        Avaliação por Escrito / Review Pessoal
                    </label>
                    <textarea
                        id="review"
                        rows="5"
                        wire:model="review"
                        placeholder="Escreva suas impressões, pontos fortes e fracos, história, gameplay e conclusões sobre o jogo..."
                        class="w-full px-3.5 py-2.5 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] font-['Roboto'] resize-none leading-relaxed transition-all"></textarea>
                    @error('review')
                    <span class="text-xs text-red-500 mt-1.5 block font-['Roboto']">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @endif

            <!-- Submit & Action Buttons (Separated with generous top margin) -->
            <div class="mt-8 pt-6 border-t border-[var(--border-color)]/60 flex items-center justify-end gap-3 sm:gap-4">
                <!-- Cancel Button identical to top button -->
                <a
                    href="{{ route('dashboard') }}"
                    class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] hover:border-[var(--brand-primary)] hover:shadow-[var(--glow-retro)] transition-all font-['Open_Sans'] flex items-center gap-1.5 cursor-pointer shadow-sm">
                    <svg class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Cancelar</span>
                </a>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="px-6 py-2.5 text-xs sm:text-sm font-semibold rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white shadow-md hover:opacity-90 hover:shadow-[var(--glow-retro)] hover:-translate-y-0.5 transition-all font-['Open_Sans'] flex items-center gap-2 cursor-pointer disabled:opacity-50">
                    <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Salvar Jogo na Biblioteca</span>
                </button>
            </div>
        </div>

        <!-- Right Side: Live Interactive Card Preview (Constrained to Realistic Card Dimensions) -->
        <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-4">
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-3">
                    <span class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider font-['Open_Sans']">
                        Pré-visualização do Card
                    </span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-[var(--brand-primary)]/15 text-[var(--brand-primary)] font-bold">
                        Tamanho Real
                    </span>
                </div>

                <!-- Game Card Preview (Constrained to Realistic Max-Width ~220px) -->
                @php
                $previewCover = 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80';
                if ($cover_file) {
                try {
                $previewCover = $cover_file->temporaryUrl();
                } catch (\Throwable) {
                $previewCover = !empty($cover_image) ? $cover_image : $previewCover;
                }
                } elseif (!empty($cover_image)) {
                $previewCover = $cover_image;
                }
                @endphp

                <div class="max-w-[220px] w-full mx-auto">
                    <div class="group relative flex flex-col bg-[var(--bg-main)] rounded-[var(--radius-md)] overflow-hidden border border-[var(--border-color)] shadow-md">
                        <!-- Cover Image Container (Strict 2:3 Aspect Ratio) -->
                        <div class="relative w-full aspect-[2/3] overflow-hidden bg-black/40">
                            <img
                                src="{{ $previewCover }}"
                                alt="{{ $title ?: 'Novo Jogo' }}"
                                onerror="this.src='https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80';"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />

                            <div class="absolute inset-0 z-10 bg-gradient-to-t from-[var(--bg-card)] via-transparent to-black/40 transition-none pointer-events-none"></div>

                            <!-- Status Badge Top Right (Se selecionado jogo existente) -->
                            @if ($existing_game_id && $status)
                            <div class="absolute top-2 right-2 z-10 drop-shadow-md">
                                <x-game.status-badge :status="$status" size="sm" />
                            </div>
                            @endif

                            <!-- Release Year Top Left -->
                            @if ($release_year)
                            <div class="absolute top-2 left-2 z-10">
                                <span class="px-1.5 py-0.5 text-[10px] font-semibold bg-black/75 text-white/90 rounded-[var(--radius-sm)]">
                                    {{ $release_year }}
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-3 flex flex-col justify-between flex-1 gap-1.5 bg-[var(--bg-card)]">
                            <div>
                                <h3 class="font-bold text-xs sm:text-sm text-[var(--text-main)] font-['Ubuntu'] leading-snug line-clamp-1">
                                    {{ $title !== '' ? $title : 'Título do Jogo' }}
                                </h3>
                                <p class="text-[11px] text-[var(--text-muted)] font-['Roboto'] line-clamp-1 mt-0.5">
                                    {{ $developer !== '' ? $developer : 'Desenvolvedora' }}
                                </p>
                            </div>

                            <!-- Card Footer -->
                            <div class="flex items-center justify-between gap-1 pt-2 border-t border-[var(--border-color)]/60 text-xs">
                                <x-game.star-rating :rating="$rating ?? 0.0" size="sm" :showNumber="true" />

                                @if ((float) $hours_played > 0)
                                <span class="text-[10px] font-medium text-[var(--text-muted)] font-['Roboto'] flex items-center gap-0.5">
                                    <svg class="w-3 h-3 text-[var(--brand-secondary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ number_format((float) $hours_played, 1) }}h
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Snippets Preview -->
                <div class="pt-2 text-xs font-['Roboto'] text-[var(--text-muted)] space-y-1.5 border-t border-[var(--border-color)]/60">
                    <div class="flex items-center justify-between">
                        <span>Plataformas:</span>
                        <strong class="text-[var(--text-main)]">{{ count($selected_platforms) }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Bibliotecas:</span>
                        <strong class="text-[var(--text-main)]">{{ count($selected_libraries) }}</strong>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>Gêneros:</span>
                        <strong class="text-[var(--text-main)]">{{ count($selected_genres) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>