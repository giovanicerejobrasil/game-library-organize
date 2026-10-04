<div class="w-full max-w-7xl mx-auto py-4"
    x-data="{
        previewPrimary: @entangle('brand_primary'),
        previewSecondary: @entangle('brand_secondary'),
        init() {
            window.addEventListener('glo-colors-applied', (e) => {
                const data = e.detail || {};
                if (data.primary) {
                    document.documentElement.style.setProperty('--brand-primary', data.primary);
                    localStorage.setItem('glo_brand_primary', data.primary);
                }
                if (data.secondary) {
                    document.documentElement.style.setProperty('--brand-secondary', data.secondary);
                    localStorage.setItem('glo_brand_secondary', data.secondary);
                }
                if (data.theme) {
                    document.documentElement.setAttribute('data-theme', data.theme);
                    if (document.body) {
                        document.body.setAttribute('data-theme', data.theme);
                    }
                    localStorage.setItem('glo_theme', data.theme);
                }
            });
        }
    }">

    <!-- Breadcrumb / Header Title -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[var(--border-color)]">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] tracking-tight text-[var(--text-main)] flex items-center gap-3">
                <span class="p-2 rounded-[var(--radius-md)] bg-[var(--brand-primary)]/15 text-[var(--brand-primary)]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
                Configurações do Usuário
            </h1>
            <p class="text-sm font-['Open_Sans'] text-[var(--text-muted)] mt-1">
                Gerencie sua conta, personalize a identidade visual retrô-moderna e visualize diagnósticos do sistema.
            </p>
        </div>

        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold font-['Open_Sans'] text-[var(--text-muted)] hover:text-[var(--text-main)] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar à Biblioteca
        </a>
    </div>

    <!-- Main Container: Sidebar + Content Area -->
    <div class="flex flex-col md:flex-row gap-6 lg:gap-8 items-start">

        <!-- Left Sidebar Navigation -->
        <aside class="w-full md:w-64 lg:w-72 shrink-0">
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-4 shadow-[var(--elevation-low)] space-y-4">

                <!-- User Summary Mini-Profile -->
                <div class="flex items-center gap-3 p-3 bg-[var(--bg-main)]/60 rounded-[var(--radius-md)] border border-[var(--border-color)]">
                    <div class="w-11 h-11 rounded-full bg-[var(--brand-primary)] text-white font-bold text-sm font-['Ubuntu'] flex items-center justify-center shrink-0 shadow-sm">
                        {{ $user->initials() }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-[var(--text-main)] truncate font-['Open_Sans']">{{ $user->name }}</p>
                        <p class="text-xs text-[var(--text-muted)] truncate font-['Roboto']">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Navigation Menu Links -->
                <nav class="space-y-1.5 font-['Open_Sans']" aria-label="Menu de Configurações">

                    <!-- Menu 1: Informações da Conta -->
                    <button type="button"
                        wire:click="setTab('account')"
                        class="w-full flex items-center gap-3 px-3.5 py-3 rounded-[var(--radius-md)] text-left text-sm font-semibold transition-all cursor-pointer {{ $tab === 'account' ? 'bg-[var(--brand-primary)] text-white shadow-md' : 'text-[var(--text-main)] hover:bg-[var(--border-color)]/40' }}">
                        <svg class="w-5 h-5 shrink-0 {{ $tab === 'account' ? 'text-white' : 'text-[var(--text-muted)]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <div>
                            <span class="block leading-tight">Informações da Conta</span>
                            <span class="block text-xs font-normal {{ $tab === 'account' ? 'text-white/80' : 'text-[var(--text-muted)]' }}">Perfil e credenciais</span>
                        </div>
                    </button>

                    <!-- Menu 2: Cores do Sistema -->
                    <button type="button"
                        wire:click="setTab('colors')"
                        class="w-full flex items-center gap-3 px-3.5 py-3 rounded-[var(--radius-md)] text-left text-sm font-semibold transition-all cursor-pointer {{ $tab === 'colors' ? 'bg-[var(--brand-primary)] text-white shadow-md' : 'text-[var(--text-main)] hover:bg-[var(--border-color)]/40' }}">
                        <svg class="w-5 h-5 shrink-0 {{ $tab === 'colors' ? 'text-white' : 'text-[var(--text-muted)]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 4 4 0 014-4h4a4 4 0 014 4 4 4 0 01-4 4H7zm0 0l-1.5-1.5M17 3a4 4 0 014 4 4 4 0 01-4 4h-4a4 4 0 01-4-4 4 4 0 014-4h4zm-5 7a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                        <div>
                            <span class="block leading-tight">Cores do Sistema</span>
                            <span class="block text-xs font-normal {{ $tab === 'colors' ? 'text-white/80' : 'text-[var(--text-muted)]' }}">Tema e paleta visual</span>
                        </div>
                    </button>

                    <!-- Menu 2.5: Plataformas, Bibliotecas e Gêneros -->
                    <button type="button"
                        wire:click="setTab('catalog')"
                        class="w-full flex items-center gap-3 px-3.5 py-3 rounded-[var(--radius-md)] text-left text-sm font-semibold transition-all cursor-pointer {{ $tab === 'catalog' ? 'bg-[var(--brand-primary)] text-white shadow-md' : 'text-[var(--text-main)] hover:bg-[var(--border-color)]/40' }}">
                        <svg class="w-5 h-5 shrink-0 {{ $tab === 'catalog' ? 'text-white' : 'text-[var(--text-muted)]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <div>
                            <span class="block leading-tight">Plataformas e Bibliotecas</span>
                            <span class="block text-xs font-normal {{ $tab === 'catalog' ? 'text-white/80' : 'text-[var(--text-muted)]' }}">Plataformas, bibliotecas e gêneros</span>
                        </div>
                    </button>

                    {{-- Menu 3: Informações do Sistema / Métricas do Catálogo --}}
                    <button type="button"
                        wire:click="setTab('system')"
                        class="w-full flex items-center gap-3 px-3.5 py-3 rounded-[var(--radius-md)] text-left text-sm font-semibold transition-all cursor-pointer {{ $tab === 'system' ? 'bg-[var(--brand-primary)] text-white shadow-md' : 'text-[var(--text-main)] hover:bg-[var(--border-color)]/40' }}">
                        <svg class="w-5 h-5 shrink-0 {{ $tab === 'system' ? 'text-white' : 'text-[var(--text-muted)]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                        <div>
                            @if ($user->isAdmin())
                            <span class="block leading-tight">Informações do Sistema</span>
                            <span class="block text-xs font-normal {{ $tab === 'system' ? 'text-white/80' : 'text-[var(--text-muted)]' }}">Infraestrutura e diagnósticos</span>
                            @else
                            <span class="block leading-tight">Métricas do Catálogo</span>
                            <span class="block text-xs font-normal {{ $tab === 'system' ? 'text-white/80' : 'text-[var(--text-muted)]' }}">Estatísticas da biblioteca</span>
                            @endif
                        </div>
                    </button>

                </nav>

                <!-- Footer tip in sidebar -->
                <div class="pt-3 border-t border-[var(--border-color)] text-xs text-[var(--text-muted)] font-['Roboto'] flex items-center gap-2">
                    @if ($user->isAdmin())
                    <span class="w-2 h-2 rounded-full bg-[var(--status-finished)] inline-block"></span>
                    <span>Stack: PostgreSQL + Elasticsearch</span>
                    @else
                    <span class="w-2 h-2 rounded-full bg-[var(--brand-primary)] inline-block"></span>
                    <span>Game Library Organize</span>
                    @endif
                </div>
            </div>
        </aside>

        <!-- Right Content Panel -->
        <div class="flex-1 w-full min-w-0">

            <!-- ====================================================================== -->
            <!-- TAB 1: Informações da Conta -->
            <!-- ====================================================================== -->
            @if ($tab === 'account')
            <div class="space-y-6">

                <!-- Section 1.1: Perfil Cadastral -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 shadow-[var(--elevation-low)]">
                    <div class="flex items-center justify-between pb-4 border-b border-[var(--border-color)] mb-5">
                        <div>
                            <h2 class="text-lg font-bold font-['Ubuntu'] text-[var(--text-main)]">Dados Cadastrais</h2>
                            <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Informações de identificação da sua conta de jogador</p>
                        </div>
                        <span class="text-xs font-['Open_Sans'] px-3 py-1 rounded-[var(--radius-sm)] bg-[var(--border-color)]/40 text-[var(--text-muted)]">
                            Membro desde {{ $user->created_at?->format('d/m/Y') ?? 'Recente' }}
                        </span>
                    </div>

                    @if (session()->has('profile_status'))
                    <div class="mb-5 p-3.5 rounded-[var(--radius-md)] bg-[var(--status-finished)]/15 border border-[var(--status-finished)]/40 text-[var(--status-finished)] text-sm font-medium flex items-center gap-2.5 font-['Open_Sans']">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('profile_status') }}</span>
                    </div>
                    @endif

                    <form wire:submit="updateProfile" class="space-y-4">
                        <div>
                            <label for="profile_name" class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                Nome Completo
                            </label>
                            <input type="text"
                                id="profile_name"
                                wire:model="name"
                                class="w-full px-3.5 py-2.5 rounded-[var(--radius-md)] bg-[var(--bg-main)] border border-[var(--border-color)] text-[var(--text-main)] text-sm font-['Roboto'] placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition-all">
                            @error('name')
                            <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="profile_email" class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                Endereço de E-mail
                            </label>
                            <input type="email"
                                id="profile_email"
                                wire:model="email"
                                class="w-full px-3.5 py-2.5 rounded-[var(--radius-md)] bg-[var(--bg-main)] border border-[var(--border-color)] text-[var(--text-main)] text-sm font-['Roboto'] placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition-all">
                            @error('email')
                            <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-3 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white text-sm font-semibold font-['Open_Sans'] shadow-md hover:opacity-90 active:scale-[0.98] transition-all cursor-pointer">
                                <svg wire:loading.remove wire:target="updateProfile" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <svg wire:loading wire:target="updateProfile" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Salvar Alterações do Perfil</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Section 1.2: Segurança & Senha -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 shadow-[var(--elevation-low)]">
                    <div class="pb-4 border-b border-[var(--border-color)] mb-5">
                        <h2 class="text-lg font-bold font-['Ubuntu'] text-[var(--text-main)]">Segurança e Acesso</h2>
                        <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Atualize sua senha de autenticação para manter sua biblioteca protegida</p>
                    </div>

                    @if (session()->has('password_status'))
                    <div class="mb-5 p-3.5 rounded-[var(--radius-md)] bg-[var(--status-finished)]/15 border border-[var(--status-finished)]/40 text-[var(--status-finished)] text-sm font-medium flex items-center gap-2.5 font-['Open_Sans']">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('password_status') }}</span>
                    </div>
                    @endif

                    <form wire:submit="updatePassword" class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                Senha Atual
                            </label>
                            <div class="relative">
                                <input type="password"
                                    id="current_password"
                                    wire:model="current_password"
                                    class="w-full px-3.5 py-2.5 pr-10 rounded-[var(--radius-md)] bg-[var(--bg-main)] border border-[var(--border-color)] text-[var(--text-main)] text-sm font-['Roboto'] placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition-all">
                                <button type="button" onclick="window.togglePasswordVisibility ? window.togglePasswordVisibility(this) : null" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[var(--text-muted)] hover:text-[var(--text-main)]">
                                    <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg class="eye-closed w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                            <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="new_password" class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                    Nova Senha
                                </label>
                                <div class="relative">
                                    <input type="password"
                                        id="new_password"
                                        wire:model="new_password"
                                        class="w-full px-3.5 py-2.5 pr-10 rounded-[var(--radius-md)] bg-[var(--bg-main)] border border-[var(--border-color)] text-[var(--text-main)] text-sm font-['Roboto'] placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition-all">
                                    <button type="button" onclick="window.togglePasswordVisibility ? window.togglePasswordVisibility(this) : null" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[var(--text-muted)] hover:text-[var(--text-main)]">
                                        <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg class="eye-closed w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                                @error('new_password')
                                <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="new_password_confirmation" class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                    Confirmar Nova Senha
                                </label>
                                <div class="relative">
                                    <input type="password"
                                        id="new_password_confirmation"
                                        wire:model="new_password_confirmation"
                                        class="w-full px-3.5 py-2.5 pr-10 rounded-[var(--radius-md)] bg-[var(--bg-main)] border border-[var(--border-color)] text-[var(--text-main)] text-sm font-['Roboto'] placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] focus:ring-1 focus:ring-[var(--brand-primary)] transition-all">
                                    <button type="button" onclick="window.togglePasswordVisibility ? window.togglePasswordVisibility(this) : null" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[var(--text-muted)] hover:text-[var(--text-main)]">
                                        <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg class="eye-closed w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-[var(--radius-md)] bg-[var(--brand-secondary)] text-white text-sm font-semibold font-['Open_Sans'] shadow-md hover:opacity-90 active:scale-[0.98] transition-all cursor-pointer">
                                <svg wire:loading.remove wire:target="updatePassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <svg wire:loading wire:target="updatePassword" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Alterar Senha de Acesso</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
            @endif

            <!-- ====================================================================== -->
            <!-- TAB 2: Cores do Sistema -->
            <!-- ====================================================================== -->
            @if ($tab === 'colors')
            <div class="space-y-6">

                @if (session()->has('colors_status'))
                <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--status-finished)]/15 border border-[var(--status-finished)]/40 text-[var(--status-finished)] text-sm font-medium flex items-center gap-2.5 font-['Open_Sans']">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('colors_status') }}</span>
                </div>
                @endif

                <!-- Section 2.1: Modo de Tema (Light / Dark) -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 shadow-[var(--elevation-low)]">
                    <div class="pb-4 border-b border-[var(--border-color)] mb-5">
                        <h2 class="text-lg font-bold font-['Ubuntu'] text-[var(--text-main)]">Modo do Tema</h2>
                        <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Escolha entre a experiência noturna imersiva ou o tema claro de alto contraste</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <!-- Dark Mode Card -->
                        <label class="relative flex flex-col p-4 rounded-[var(--radius-md)] border-2 cursor-pointer transition-all {{ $theme === 'dark' ? 'border-[var(--brand-primary)] bg-[var(--brand-primary)]/10 shadow-md' : 'border-[var(--border-color)] hover:border-[var(--brand-primary)]/50 bg-[var(--bg-main)]/50' }}">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-bold text-sm font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[var(--text-main)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                    </svg>
                                    Modo Escuro (Padrão)
                                </span>
                                <input type="radio" wire:model.live="theme" value="dark" class="text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div class="w-full h-16 rounded-[var(--radius-sm)] bg-[#0f0f0f] border border-[#2a2a3b] p-2 flex items-center gap-2">
                                <div class="w-1/3 h-full rounded bg-[#1a1a24] border border-[#2a2a3b]"></div>
                                <div class="w-2/3 h-full rounded bg-[#182075]/40 flex items-center px-2">
                                    <div class="h-2 w-12 bg-[#fafafa] rounded"></div>
                                </div>
                            </div>
                            <span class="text-xs text-[var(--text-muted)] font-['Roboto'] mt-2">Visual gamer retrô-moderno com fundo escuro (#0F0F0F)</span>
                        </label>

                        <!-- Light Mode Card -->
                        <label class="relative flex flex-col p-4 rounded-[var(--radius-md)] border-2 cursor-pointer transition-all {{ $theme === 'light' ? 'border-[var(--brand-primary)] bg-[var(--brand-primary)]/10 shadow-md' : 'border-[var(--border-color)] hover:border-[var(--brand-primary)]/50 bg-[var(--bg-main)]/50' }}">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-bold text-sm font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    Modo Claro
                                </span>
                                <input type="radio" wire:model.live="theme" value="light" class="text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]">
                            </div>
                            <div class="w-full h-16 rounded-[var(--radius-sm)] bg-[#fafafa] border border-[#e0e0e0] p-2 flex items-center gap-2">
                                <div class="w-1/3 h-full rounded bg-[#ffffff] border border-[#e0e0e0]"></div>
                                <div class="w-2/3 h-full rounded bg-[#182075]/20 flex items-center px-2">
                                    <div class="h-2 w-12 bg-[#0f0f0f] rounded"></div>
                                </div>
                            </div>
                            <span class="text-xs text-[var(--text-muted)] font-['Roboto'] mt-2">Design limpo e claro com alta legibilidade (#FAFAFA)</span>
                        </label>

                    </div>
                </div>

                <!-- Section 2.2: Paleta de Cores e Seletores -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 shadow-[var(--elevation-low)] space-y-6">
                    <div class="pb-4 border-b border-[var(--border-color)]">
                        <h2 class="text-lg font-bold font-['Ubuntu'] text-[var(--text-main)]">Paleta de Cores do Usuário</h2>
                        <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Defina as cores personalizadas injetadas nas variáveis CSS (<code class="text-xs font-mono">--brand-primary</code> e <code class="text-xs font-mono">--brand-secondary</code>)</p>
                    </div>

                    <!-- Presets Grid -->
                    <div>
                        <span class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-2.5 font-['Open_Sans']">
                            Paletas Pré-definidas (1 Clique)
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach ($presets as $preset)
                            <button type="button"
                                wire:click="applyPreset('{{ $preset['primary'] }}', '{{ $preset['secondary'] }}')"
                                class="p-3 rounded-[var(--radius-md)] border border-[var(--border-color)] hover:border-[var(--brand-primary)] bg-[var(--bg-main)]/60 text-left transition-all hover:scale-[1.01] cursor-pointer group">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-xs font-['Ubuntu'] text-[var(--text-main)] group-hover:text-[var(--brand-primary)] transition-colors">
                                        {{ $preset['name'] }}
                                    </span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-4 h-4 rounded-full shadow-sm border border-black/20" style="background-color: {{ $preset['primary'] }}"></span>
                                        <span class="w-4 h-4 rounded-full shadow-sm border border-black/20" style="background-color: {{ $preset['secondary'] }}"></span>
                                    </div>
                                </div>
                                <p class="text-[11px] text-[var(--text-muted)] font-['Roboto'] line-clamp-1">{{ $preset['description'] }}</p>
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Custom Color Pickers -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">

                        <!-- Primary Color Picker -->
                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)] space-y-3">
                            <div class="flex items-center justify-between">
                                <label for="brand_primary_input" class="text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider font-['Open_Sans']">
                                    Cor Primária
                                </label>
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-[var(--border-color)]/50 text-[var(--text-muted)]">--brand-primary</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="color"
                                    id="brand_primary_color"
                                    wire:model.live="brand_primary"
                                    class="w-12 h-11 rounded-[var(--radius-sm)] border border-[var(--border-color)] cursor-pointer bg-transparent p-0.5">

                                <div class="flex-1">
                                    <input type="text"
                                        id="brand_primary_input"
                                        wire:model.live="brand_primary"
                                        placeholder="#182075"
                                        maxlength="7"
                                        class="w-full px-3 py-2 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] font-mono text-sm focus:outline-none focus:border-[var(--brand-primary)]">
                                </div>
                            </div>
                            <p class="text-xs text-[var(--text-muted)] font-['Roboto']">
                                Usada na barra de cabeçalho, botões principais de ação e detalhes em destaque do dashboard.
                            </p>
                            @error('brand_primary')
                            <p class="text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Secondary Color Picker -->
                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)] space-y-3">
                            <div class="flex items-center justify-between">
                                <label for="brand_secondary_input" class="text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider font-['Open_Sans']">
                                    Cor Secundária
                                </label>
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-[var(--border-color)]/50 text-[var(--text-muted)]">--brand-secondary</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="color"
                                    id="brand_secondary_color"
                                    wire:model.live="brand_secondary"
                                    class="w-12 h-11 rounded-[var(--radius-sm)] border border-[var(--border-color)] cursor-pointer bg-transparent p-0.5">

                                <div class="flex-1">
                                    <input type="text"
                                        id="brand_secondary_input"
                                        wire:model.live="brand_secondary"
                                        placeholder="#751919"
                                        maxlength="7"
                                        class="w-full px-3 py-2 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] font-mono text-sm focus:outline-none focus:border-[var(--brand-secondary)]">
                                </div>
                            </div>
                            <p class="text-xs text-[var(--text-muted)] font-['Roboto']">
                                Usada para ações de destaque secundárias, botões críticos e badges de ênfase visual.
                            </p>
                            @error('brand_secondary')
                            <p class="text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Live Preview Component Card -->
                    <div class="mt-4 p-5 rounded-[var(--radius-md)] border border-[var(--border-color)] bg-[var(--bg-main)] space-y-3">
                        <span class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider font-['Open_Sans']">
                            Pré-visualização em Tempo Real
                        </span>

                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] shadow-[var(--elevation-low)] space-y-3">
                            <div class="flex items-center justify-between pb-3 border-b border-[var(--border-color)]">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full" :style="'background-color: ' + previewPrimary"></span>
                                    <span class="font-bold text-sm font-['Ubuntu'] text-[var(--text-main)]">Amostra de Elementos da Interface</span>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-[var(--radius-sm)] text-white shadow-sm" :style="'background-color: ' + previewSecondary">
                                    Badge Secundária
                                </span>
                            </div>

                            <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">
                                Veja como seus botões de ação e componentes interativos serão renderizados em todas as telas da plataforma.
                            </p>

                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <button type="button" class="px-4 py-2 rounded-[var(--radius-md)] text-white text-xs font-bold font-['Open_Sans'] shadow-md transition-transform hover:scale-105" :style="'background-color: ' + previewPrimary">
                                    Botão Primário
                                </button>

                                <button type="button" class="px-4 py-2 rounded-[var(--radius-md)] text-white text-xs font-bold font-['Open_Sans'] shadow-md transition-transform hover:scale-105" :style="'background-color: ' + previewSecondary">
                                    Botão Secundário
                                </button>

                                <div class="px-3 py-1.5 rounded-[var(--radius-sm)] border text-xs font-medium font-['Roboto']" :style="'border-color: ' + previewPrimary + '; color: ' + previewPrimary">
                                    Borda Realçada
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-[var(--border-color)]">
                        <button type="button"
                            wire:click="resetColors"
                            wire:confirm="Deseja restaurar as cores e o tema padrão do Game Library Organize?"
                            class="px-4 py-2.5 rounded-[var(--radius-md)] border border-[var(--border-color)] text-xs font-semibold font-['Open_Sans'] text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-[var(--border-color)]/30 transition-all cursor-pointer">
                            Restaurar Padrões do Sistema
                        </button>

                        <button type="button"
                            wire:click="saveColors"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white text-sm font-semibold font-['Open_Sans'] shadow-md hover:opacity-90 active:scale-[0.98] transition-all cursor-pointer">
                            <svg wire:loading.remove wire:target="saveColors" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg wire:loading wire:target="saveColors" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span>Salvar Preferências Visuais</span>
                        </button>
                    </div>

                </div>

            </div>
            @endif

            <!-- ====================================================================== -->
            <!-- TAB 2.5: Plataformas, Bibliotecas e Gêneros -->
            <!-- ====================================================================== -->
            @if ($tab === 'catalog')
            <div class="space-y-6">

                <!-- Header / Sub-tab Navigation -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-5 sm:p-6 shadow-[var(--elevation-low)] space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[var(--border-color)]">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2.5">
                                <span class="p-1.5 rounded-[var(--radius-sm)] bg-[var(--brand-primary)]/15 text-[var(--brand-primary)]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </span>
                                Gerenciamento do Catálogo
                            </h2>
                            <p class="text-xs font-['Roboto'] text-[var(--text-muted)] mt-1">
                                Cadastre e personalize novas plataformas, bibliotecas digitais para PC e gêneros com suporte a cores e cópia interativa.
                            </p>
                        </div>
                    </div>

                    <!-- Sub-tabs Buttons -->
                    <div class="flex flex-wrap items-center gap-2 border-b border-[var(--border-color)] pb-3">
                        <button type="button"
                            wire:click="setCatalogSubTab('platforms')"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-[var(--radius-md)] text-xs sm:text-sm font-semibold font-['Open_Sans'] transition-all cursor-pointer {{ $catalogSubTab === 'platforms' ? 'bg-[var(--brand-primary)] text-white shadow-md' : 'text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-[var(--border-color)]/40 bg-[var(--bg-main)]' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            <span>Plataformas ({{ $platforms->count() }})</span>
                        </button>

                        <button type="button"
                            wire:click="setCatalogSubTab('libraries')"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-[var(--radius-md)] text-xs sm:text-sm font-semibold font-['Open_Sans'] transition-all cursor-pointer {{ $catalogSubTab === 'libraries' ? 'bg-[var(--brand-primary)] text-white shadow-md' : 'text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-[var(--border-color)]/40 bg-[var(--bg-main)]' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Bibliotecas para PC ({{ $libraries->count() }})</span>
                        </button>

                        <button type="button"
                            wire:click="setCatalogSubTab('genres')"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-[var(--radius-md)] text-xs sm:text-sm font-semibold font-['Open_Sans'] transition-all cursor-pointer {{ $catalogSubTab === 'genres' ? 'bg-[var(--brand-primary)] text-white shadow-md' : 'text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-[var(--border-color)]/40 bg-[var(--bg-main)]' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span>Gêneros ({{ $genres->count() }})</span>
                        </button>
                    </div>

                    <!-- ============================================================== -->
                    <!-- SUBTAB 1: PLATAFORMAS -->
                    <!-- ============================================================== -->
                    @if ($catalogSubTab === 'platforms')
                    <div class="space-y-6 pt-2">

                        @if (session()->has('platform_status'))
                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--status-finished)]/15 border border-[var(--status-finished)]/40 text-[var(--status-finished)] text-sm font-medium flex items-center gap-2.5 font-['Open_Sans']">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ session('platform_status') }}</span>
                        </div>
                        @endif

                        @if (session()->has('platform_error'))
                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--status-dropped)]/15 border border-[var(--status-dropped)]/40 text-[var(--status-dropped)] text-sm font-medium flex items-center gap-2.5 font-['Open_Sans']">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>{{ session('platform_error') }}</span>
                        </div>
                        @endif

                        <!-- Formulário de Edição Inline (se ativo) -->
                        @if ($editing_platform_id)
                        <div class="p-5 rounded-[var(--radius-md)] bg-[var(--bg-main)] border-2 border-[var(--brand-primary)] space-y-4 shadow-md">
                            <div class="flex items-center justify-between pb-2 border-b border-[var(--border-color)]">
                                <h3 class="text-sm font-bold font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar Plataforma
                                </h3>
                                <button type="button" wire:click="cancelEditPlatform" class="text-xs text-[var(--text-muted)] hover:text-[var(--text-main)] cursor-pointer transition-colors">
                                    Cancelar
                                </button>
                            </div>

                            <form wire:submit="updatePlatform" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <div class="md:col-span-6">
                                    <label class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                        Nome da Plataforma
                                    </label>
                                    <input type="text"
                                        wire:model="edit_platform_name"
                                        class="h-11 w-full px-3.5 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] text-sm font-['Roboto'] focus:outline-none focus:border-[var(--brand-primary)] box-border">
                                    @error('edit_platform_name')
                                    <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                        Cor da Plataforma
                                    </label>
                                    <div class="flex items-center gap-2.5">
                                        <div class="relative w-11 h-11 rounded-[var(--radius-sm)] border border-[var(--border-color)] overflow-hidden shadow-sm shrink-0 cursor-pointer box-border"
                                            style="background-color: {{ $edit_platform_color }}">
                                            <input type="color" wire:model.live="edit_platform_color" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer">
                                        </div>
                                        <input type="text"
                                            wire:model.live="edit_platform_color"
                                            maxlength="7"
                                            class="h-11 w-full px-3 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] font-mono text-xs uppercase focus:outline-none focus:border-[var(--brand-primary)] box-border">
                                    </div>
                                    @error('edit_platform_color')
                                    <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-3 flex items-center gap-2">
                                    <button type="submit"
                                        class="h-11 w-full inline-flex items-center justify-center px-4 rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white text-xs sm:text-sm font-bold font-['Open_Sans'] shadow-md hover:opacity-90 active:scale-95 transition-all cursor-pointer box-border">
                                        Salvar
                                    </button>
                                    <button type="button"
                                        wire:click="cancelEditPlatform"
                                        class="h-11 inline-flex items-center justify-center px-4 rounded-[var(--radius-md)] border border-[var(--border-color)] text-[var(--text-muted)] hover:text-[var(--text-main)] text-xs sm:text-sm font-semibold font-['Open_Sans'] transition-all cursor-pointer box-border shrink-0">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                        @else
                        <!-- Inserção de Nova Plataforma -->
                        <div class="p-5 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)] space-y-4">
                            <div>
                                <h3 class="text-sm font-bold font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Cadastrar Nova Plataforma
                                </h3>
                                <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Adicione novos consoles, portáteis ou ecossistemas de jogos com cor personalizada</p>
                            </div>

                            <form wire:submit="createPlatform" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <div class="md:col-span-6">
                                    <label for="new_platform_name" class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                        Nome da Plataforma
                                    </label>
                                    <input type="text"
                                        id="new_platform_name"
                                        wire:model="platform_name"
                                        placeholder="Ex: PlayStation 2, Nintendo 64, Atari..."
                                        class="h-11 w-full px-3.5 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] text-sm font-['Roboto'] placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] box-border">
                                    @error('platform_name')
                                    <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-3">
                                    <label for="new_platform_color" class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                        Cor de Identificação
                                    </label>
                                    <div class="flex items-center gap-2.5">
                                        <div class="relative w-11 h-11 rounded-[var(--radius-sm)] border border-[var(--border-color)] overflow-hidden shadow-sm shrink-0 cursor-pointer hover:scale-105 transition-transform box-border"
                                            style="background-color: {{ $platform_color }}">
                                            <input type="color" id="new_platform_color" wire:model.live="platform_color" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer">
                                        </div>
                                        <input type="text"
                                            wire:model.live="platform_color"
                                            maxlength="7"
                                            placeholder="#182075"
                                            class="h-11 w-full px-3 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] font-mono text-xs uppercase focus:outline-none focus:border-[var(--brand-primary)] box-border">
                                    </div>
                                    @error('platform_color')
                                    <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-3">
                                    <button type="submit"
                                        class="h-11 w-full inline-flex items-center justify-center gap-2 px-5 rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white text-xs sm:text-sm font-bold font-['Open_Sans'] shadow-md hover:opacity-90 active:scale-[0.98] transition-all cursor-pointer box-border">
                                        <svg wire:loading.remove wire:target="createPlatform" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <svg wire:loading wire:target="createPlatform" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        <span>Adicionar Plataforma</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Listagem das Plataformas -->
                        <div class="space-y-3 pt-2">
                            <span class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider font-['Open_Sans']">
                                Plataformas Cadastradas ({{ $platforms->count() }})
                            </span>

                            <div class="overflow-x-auto rounded-[var(--radius-md)] border border-[var(--border-color)]">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-[var(--bg-main)]/80 border-b border-[var(--border-color)] text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider font-['Open_Sans']">
                                            <th class="py-3 px-4">Cor</th>
                                            <th class="py-3 px-4">Plataforma</th>
                                            <th class="py-3 px-4">Códigos de Cor (Clique para Copiar)</th>
                                            <th class="py-3 px-4 text-center">Origem</th>
                                            <th class="py-3 px-4 text-right">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[var(--border-color)] font-['Roboto'] text-sm bg-[var(--bg-card)]">
                                        @forelse ($platforms as $platform)
                                        @php
                                        $formats = \App\Services\Support\ColorConverter::toAllFormats($platform->resolved_color);
                                        $canManage = $platform->canBeManagedBy($user);
                                        @endphp
                                        <tr class="hover:bg-[var(--bg-main)]/40 transition-colors">
                                            <!-- Quadrado com a cor -->
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <div class="w-8 h-8 rounded-[var(--radius-sm)] border border-black/20 shadow-sm flex items-center justify-center shrink-0 cursor-pointer transition-transform hover:scale-110"
                                                    style="background-color: {{ $platform->resolved_color }}"
                                                    title="Cor: {{ $formats['hex'] }} (Clique em uma das badges para copiar)">
                                                </div>
                                            </td>

                                            <!-- Nome da plataforma -->
                                            <td class="py-3 px-4 font-semibold text-[var(--text-main)] font-['Ubuntu']">
                                                {{ $platform->name }}
                                            </td>

                                            <!-- Códigos HEX, RGB e HSL -->
                                            <td class="py-3 px-4">
                                                <div class="flex flex-wrap items-center gap-1.5 font-mono text-xs">
                                                    <!-- Badge HEX -->
                                                    <button type="button"
                                                        x-data="{
                                                            copied: false,
                                                            copy(val) {
                                                                if (navigator.clipboard && window.isSecureContext) {
                                                                    navigator.clipboard.writeText(val).then(() => {
                                                                        this.copied = true;
                                                                        setTimeout(() => this.copied = false, 1800);
                                                                    }).catch(() => this.fallback(val));
                                                                } else {
                                                                    this.fallback(val);
                                                                }
                                                            },
                                                            fallback(val) {
                                                                const el = document.createElement('textarea');
                                                                el.value = val;
                                                                el.setAttribute('readonly', '');
                                                                el.style.position = 'absolute';
                                                                el.style.left = '-9999px';
                                                                document.body.appendChild(el);
                                                                el.select();
                                                                try {
                                                                    document.execCommand('copy');
                                                                    this.copied = true;
                                                                    setTimeout(() => this.copied = false, 1800);
                                                                } catch (e) {}
                                                                document.body.removeChild(el);
                                                            }
                                                        }"
                                                        @click="copy('{{ $formats['hex'] }}')"
                                                        class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-sm)] border border-[var(--border-color)] bg-[var(--bg-main)] hover:border-[var(--brand-primary)] text-[var(--text-main)] transition-all cursor-pointer font-mono text-xs select-none"
                                                        title="Copiar código HEX: {{ $formats['hex'] }}">
                                                        <span x-show="!copied" class="inline-flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)] group-hover:text-[var(--brand-primary)] transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                            </svg>
                                                            <span class="font-semibold text-[10px] text-[var(--text-muted)]">HEX</span>
                                                            <span>{{ $formats['hex'] }}</span>
                                                        </span>
                                                        <span x-show="copied" x-cloak class="inline-flex items-center gap-1 text-[var(--status-finished)] font-bold font-['Open_Sans']">
                                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span>COPIADO!</span>
                                                        </span>
                                                    </button>

                                                    <!-- Badge RGB -->
                                                    <button type="button"
                                                        x-data="{
                                                            copied: false,
                                                            copy(val) {
                                                                if (navigator.clipboard && window.isSecureContext) {
                                                                    navigator.clipboard.writeText(val).then(() => {
                                                                        this.copied = true;
                                                                        setTimeout(() => this.copied = false, 1800);
                                                                    }).catch(() => this.fallback(val));
                                                                } else {
                                                                    this.fallback(val);
                                                                }
                                                            },
                                                            fallback(val) {
                                                                const el = document.createElement('textarea');
                                                                el.value = val;
                                                                el.setAttribute('readonly', '');
                                                                el.style.position = 'absolute';
                                                                el.style.left = '-9999px';
                                                                document.body.appendChild(el);
                                                                el.select();
                                                                try {
                                                                    document.execCommand('copy');
                                                                    this.copied = true;
                                                                    setTimeout(() => this.copied = false, 1800);
                                                                } catch (e) {}
                                                                document.body.removeChild(el);
                                                            }
                                                        }"
                                                        @click="copy('{{ $formats['rgb'] }}')"
                                                        class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-sm)] border border-[var(--border-color)] bg-[var(--bg-main)] hover:border-[var(--brand-primary)] text-[var(--text-main)] transition-all cursor-pointer font-mono text-xs select-none"
                                                        title="Copiar código RGB: {{ $formats['rgb'] }}">
                                                        <span x-show="!copied" class="inline-flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)] group-hover:text-[var(--brand-primary)] transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                            </svg>
                                                            <span class="font-semibold text-[10px] text-[var(--text-muted)]">RGB</span>
                                                            <span>{{ $formats['rgb'] }}</span>
                                                        </span>
                                                        <span x-show="copied" x-cloak class="inline-flex items-center gap-1 text-[var(--status-finished)] font-bold font-['Open_Sans']">
                                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span>COPIADO!</span>
                                                        </span>
                                                    </button>

                                                    <!-- Badge HSL -->
                                                    <button type="button"
                                                        x-data="{
                                                            copied: false,
                                                            copy(val) {
                                                                if (navigator.clipboard && window.isSecureContext) {
                                                                    navigator.clipboard.writeText(val).then(() => {
                                                                        this.copied = true;
                                                                        setTimeout(() => this.copied = false, 1800);
                                                                    }).catch(() => this.fallback(val));
                                                                } else {
                                                                    this.fallback(val);
                                                                }
                                                            },
                                                            fallback(val) {
                                                                const el = document.createElement('textarea');
                                                                el.value = val;
                                                                el.setAttribute('readonly', '');
                                                                el.style.position = 'absolute';
                                                                el.style.left = '-9999px';
                                                                document.body.appendChild(el);
                                                                el.select();
                                                                try {
                                                                    document.execCommand('copy');
                                                                    this.copied = true;
                                                                    setTimeout(() => this.copied = false, 1800);
                                                                } catch (e) {}
                                                                document.body.removeChild(el);
                                                            }
                                                        }"
                                                        @click="copy('{{ $formats['hsl'] }}')"
                                                        class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-sm)] border border-[var(--border-color)] bg-[var(--bg-main)] hover:border-[var(--brand-primary)] text-[var(--text-main)] transition-all cursor-pointer font-mono text-xs select-none"
                                                        title="Copiar código HSL: {{ $formats['hsl'] }}">
                                                        <span x-show="!copied" class="inline-flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)] group-hover:text-[var(--brand-primary)] transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                            </svg>
                                                            <span class="font-semibold text-[10px] text-[var(--text-muted)]">HSL</span>
                                                            <span>{{ $formats['hsl'] }}</span>
                                                        </span>
                                                        <span x-show="copied" x-cloak class="inline-flex items-center gap-1 text-[var(--status-finished)] font-bold font-['Open_Sans']">
                                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span>COPIADO!</span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </td>

                                            <!-- Origem / Tipo -->
                                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                                @if (! $platform->is_custom)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/10 text-indigo-400 font-['Open_Sans']">
                                                    Sistema
                                                </span>
                                                @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 font-['Open_Sans']">
                                                    Personalizada
                                                </span>
                                                @endif
                                            </td>

                                            <!-- Ações -->
                                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                                @if ($canManage)
                                                <div class="inline-flex items-center gap-1">
                                                    <button type="button"
                                                        wire:click="editPlatform({{ $platform->id }})"
                                                        class="p-1.5 rounded-[var(--radius-sm)] border border-[var(--border-color)] hover:border-[var(--brand-primary)] text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-[var(--bg-main)] transition-all cursor-pointer"
                                                        title="Editar plataforma">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    <button type="button"
                                                        wire:click="deletePlatform({{ $platform->id }})"
                                                        wire:confirm="Tem certeza de que deseja excluir a plataforma '{{ $platform->name }}'?"
                                                        class="p-1.5 rounded-[var(--radius-sm)] border border-[var(--border-color)] hover:border-[var(--status-dropped)] text-[var(--text-muted)] hover:text-[var(--status-dropped)] hover:bg-[var(--status-dropped)]/10 transition-all cursor-pointer"
                                                        title="Excluir plataforma">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                @else
                                                <span class="inline-flex items-center gap-1 text-xs text-[var(--text-muted)] font-['Roboto']" title="Itens padrão do sistema não podem ser alterados por usuários comuns">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                    Bloqueado
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="py-8 text-center text-xs text-[var(--text-muted)]">
                                                Nenhuma plataforma cadastrada encontrada.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    @endif

                    <!-- ============================================================== -->
                    <!-- SUBTAB 2: BIBLIOTECAS PARA PC -->
                    <!-- ============================================================== -->
                    @if ($catalogSubTab === 'libraries')
                    <div class="space-y-6 pt-2">

                        @if (session()->has('library_status'))
                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--status-finished)]/15 border border-[var(--status-finished)]/40 text-[var(--status-finished)] text-sm font-medium flex items-center gap-2.5 font-['Open_Sans']">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ session('library_status') }}</span>
                        </div>
                        @endif

                        @if (session()->has('library_error'))
                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--status-dropped)]/15 border border-[var(--status-dropped)]/40 text-[var(--status-dropped)] text-sm font-medium flex items-center gap-2.5 font-['Open_Sans']">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>{{ session('library_error') }}</span>
                        </div>
                        @endif

                        <!-- Formulário de Edição Inline (se ativo) -->
                        @if ($editing_library_id)
                        <div class="p-5 rounded-[var(--radius-md)] bg-[var(--bg-main)] border-2 border-[var(--brand-primary)] space-y-4 shadow-md">
                            <div class="flex items-center justify-between pb-2 border-b border-[var(--border-color)]">
                                <h3 class="text-sm font-bold font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar Biblioteca Digital (PC)
                                </h3>
                                <button type="button" wire:click="cancelEditLibrary" class="text-xs text-[var(--text-muted)] hover:text-[var(--text-main)] cursor-pointer transition-colors">
                                    Cancelar
                                </button>
                            </div>

                            <form wire:submit="updateLibrary" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <div class="md:col-span-6">
                                    <label class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                        Nome da Biblioteca
                                    </label>
                                    <input type="text"
                                        wire:model="edit_library_name"
                                        class="h-11 w-full px-3.5 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] text-sm font-['Roboto'] focus:outline-none focus:border-[var(--brand-primary)] box-border">
                                    @error('edit_library_name')
                                    <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                        Cor da Biblioteca
                                    </label>
                                    <div class="flex items-center gap-2.5">
                                        <div class="relative w-11 h-11 rounded-[var(--radius-sm)] border border-[var(--border-color)] overflow-hidden shadow-sm shrink-0 cursor-pointer box-border"
                                            style="background-color: {{ $edit_library_color }}">
                                            <input type="color" wire:model.live="edit_library_color" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer">
                                        </div>
                                        <input type="text"
                                            wire:model.live="edit_library_color"
                                            maxlength="7"
                                            class="h-11 w-full px-3 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] font-mono text-xs uppercase focus:outline-none focus:border-[var(--brand-primary)] box-border">
                                    </div>
                                    @error('edit_library_color')
                                    <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-3 flex items-center gap-2">
                                    <button type="submit"
                                        class="h-11 w-full inline-flex items-center justify-center px-4 rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white text-xs sm:text-sm font-bold font-['Open_Sans'] shadow-md hover:opacity-90 active:scale-95 transition-all cursor-pointer box-border">
                                        Salvar
                                    </button>
                                    <button type="button"
                                        wire:click="cancelEditLibrary"
                                        class="h-11 inline-flex items-center justify-center px-4 rounded-[var(--radius-md)] border border-[var(--border-color)] text-[var(--text-muted)] hover:text-[var(--text-main)] text-xs sm:text-sm font-semibold font-['Open_Sans'] transition-all cursor-pointer box-border shrink-0">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                        @else
                        <!-- Inserção de Nova Biblioteca para PC -->
                        <div class="p-5 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)] space-y-4">
                            <div>
                                <h3 class="text-sm font-bold font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Cadastrar Nova Biblioteca Digital (PC)
                                </h3>
                                <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Adicione novos launchers ou lojas de jogos de computador (ex: itch.io, Battle.net, etc.)</p>
                            </div>

                            <form wire:submit="createLibrary" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <div class="md:col-span-6">
                                    <label for="new_library_name" class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                        Nome da Biblioteca
                                    </label>
                                    <input type="text"
                                        id="new_library_name"
                                        wire:model="library_name"
                                        placeholder="Ex: itch.io, Battle.net, Prime Gaming..."
                                        class="h-11 w-full px-3.5 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] text-sm font-['Roboto'] placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] box-border">
                                    @error('library_name')
                                    <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-3">
                                    <label for="new_library_color" class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                        Cor de Identificação
                                    </label>
                                    <div class="flex items-center gap-2.5">
                                        <div class="relative w-11 h-11 rounded-[var(--radius-sm)] border border-[var(--border-color)] overflow-hidden shadow-sm shrink-0 cursor-pointer hover:scale-105 transition-transform box-border"
                                            style="background-color: {{ $library_color }}">
                                            <input type="color" id="new_library_color" wire:model.live="library_color" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer">
                                        </div>
                                        <input type="text"
                                            wire:model.live="library_color"
                                            maxlength="7"
                                            placeholder="#1D2C4B"
                                            class="h-11 w-full px-3 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] font-mono text-xs uppercase focus:outline-none focus:border-[var(--brand-primary)] box-border">
                                    </div>
                                    @error('library_color')
                                    <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-3">
                                    <button type="submit"
                                        class="h-11 w-full inline-flex items-center justify-center gap-2 px-5 rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white text-xs sm:text-sm font-bold font-['Open_Sans'] shadow-md hover:opacity-90 active:scale-[0.98] transition-all cursor-pointer box-border">
                                        <svg wire:loading.remove wire:target="createLibrary" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <svg wire:loading wire:target="createLibrary" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        <span>Adicionar Biblioteca</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Listagem das Bibliotecas -->
                        <div class="space-y-3 pt-2">
                            <span class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider font-['Open_Sans']">
                                Bibliotecas para PC Cadastradas ({{ $libraries->count() }})
                            </span>

                            <div class="overflow-x-auto rounded-[var(--radius-md)] border border-[var(--border-color)]">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-[var(--bg-main)]/80 border-b border-[var(--border-color)] text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider font-['Open_Sans']">
                                            <th class="py-3 px-4">Cor</th>
                                            <th class="py-3 px-4">Biblioteca (Launcher/Loja)</th>
                                            <th class="py-3 px-4">Códigos de Cor (Clique para Copiar)</th>
                                            <th class="py-3 px-4 text-center">Origem</th>
                                            <th class="py-3 px-4 text-right">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[var(--border-color)] font-['Roboto'] text-sm bg-[var(--bg-card)]">
                                        @forelse ($libraries as $library)
                                        @php
                                        $formats = \App\Services\Support\ColorConverter::toAllFormats($library->resolved_color);
                                        $canManage = $library->canBeManagedBy($user);
                                        @endphp
                                        <tr class="hover:bg-[var(--bg-main)]/40 transition-colors">
                                            <!-- Quadrado com a cor -->
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <div class="w-8 h-8 rounded-[var(--radius-sm)] border border-black/20 shadow-sm flex items-center justify-center shrink-0 cursor-pointer transition-transform hover:scale-110"
                                                    style="background-color: {{ $library->resolved_color }}"
                                                    title="Cor: {{ $formats['hex'] }} (Clique em uma das badges para copiar)">
                                                </div>
                                            </td>

                                            <!-- Nome da biblioteca -->
                                            <td class="py-3 px-4 font-semibold text-[var(--text-main)] font-['Ubuntu']">
                                                {{ $library->name }}
                                            </td>

                                            <!-- Códigos HEX, RGB e HSL -->
                                            <td class="py-3 px-4">
                                                <div class="flex flex-wrap items-center gap-1.5 font-mono text-xs">
                                                    <!-- Badge HEX -->
                                                    <button type="button"
                                                        x-data="{
                                                            copied: false,
                                                            copy(val) {
                                                                if (navigator.clipboard && window.isSecureContext) {
                                                                    navigator.clipboard.writeText(val).then(() => {
                                                                        this.copied = true;
                                                                        setTimeout(() => this.copied = false, 1800);
                                                                    }).catch(() => this.fallback(val));
                                                                } else {
                                                                    this.fallback(val);
                                                                }
                                                            },
                                                            fallback(val) {
                                                                const el = document.createElement('textarea');
                                                                el.value = val;
                                                                el.setAttribute('readonly', '');
                                                                el.style.position = 'absolute';
                                                                el.style.left = '-9999px';
                                                                document.body.appendChild(el);
                                                                el.select();
                                                                try {
                                                                    document.execCommand('copy');
                                                                    this.copied = true;
                                                                    setTimeout(() => this.copied = false, 1800);
                                                                } catch (e) {}
                                                                document.body.removeChild(el);
                                                            }
                                                        }"
                                                        @click="copy('{{ $formats['hex'] }}')"
                                                        class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-sm)] border border-[var(--border-color)] bg-[var(--bg-main)] hover:border-[var(--brand-primary)] text-[var(--text-main)] transition-all cursor-pointer font-mono text-xs select-none"
                                                        title="Copiar código HEX: {{ $formats['hex'] }}">
                                                        <span x-show="!copied" class="inline-flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)] group-hover:text-[var(--brand-primary)] transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                            </svg>
                                                            <span class="font-semibold text-[10px] text-[var(--text-muted)]">HEX</span>
                                                            <span>{{ $formats['hex'] }}</span>
                                                        </span>
                                                        <span x-show="copied" x-cloak class="inline-flex items-center gap-1 text-[var(--status-finished)] font-bold font-['Open_Sans']">
                                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span>COPIADO!</span>
                                                        </span>
                                                    </button>

                                                    <!-- Badge RGB -->
                                                    <button type="button"
                                                        x-data="{
                                                            copied: false,
                                                            copy(val) {
                                                                if (navigator.clipboard && window.isSecureContext) {
                                                                    navigator.clipboard.writeText(val).then(() => {
                                                                        this.copied = true;
                                                                        setTimeout(() => this.copied = false, 1800);
                                                                    }).catch(() => this.fallback(val));
                                                                } else {
                                                                    this.fallback(val);
                                                                }
                                                            },
                                                            fallback(val) {
                                                                const el = document.createElement('textarea');
                                                                el.value = val;
                                                                el.setAttribute('readonly', '');
                                                                el.style.position = 'absolute';
                                                                el.style.left = '-9999px';
                                                                document.body.appendChild(el);
                                                                el.select();
                                                                try {
                                                                    document.execCommand('copy');
                                                                    this.copied = true;
                                                                    setTimeout(() => this.copied = false, 1800);
                                                                } catch (e) {}
                                                                document.body.removeChild(el);
                                                            }
                                                        }"
                                                        @click="copy('{{ $formats['rgb'] }}')"
                                                        class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-sm)] border border-[var(--border-color)] bg-[var(--bg-main)] hover:border-[var(--brand-primary)] text-[var(--text-main)] transition-all cursor-pointer font-mono text-xs select-none"
                                                        title="Copiar código RGB: {{ $formats['rgb'] }}">
                                                        <span x-show="!copied" class="inline-flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)] group-hover:text-[var(--brand-primary)] transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                            </svg>
                                                            <span class="font-semibold text-[10px] text-[var(--text-muted)]">RGB</span>
                                                            <span>{{ $formats['rgb'] }}</span>
                                                        </span>
                                                        <span x-show="copied" x-cloak class="inline-flex items-center gap-1 text-[var(--status-finished)] font-bold font-['Open_Sans']">
                                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span>COPIADO!</span>
                                                        </span>
                                                    </button>

                                                    <!-- Badge HSL -->
                                                    <button type="button"
                                                        x-data="{
                                                            copied: false,
                                                            copy(val) {
                                                                if (navigator.clipboard && window.isSecureContext) {
                                                                    navigator.clipboard.writeText(val).then(() => {
                                                                        this.copied = true;
                                                                        setTimeout(() => this.copied = false, 1800);
                                                                    }).catch(() => this.fallback(val));
                                                                } else {
                                                                    this.fallback(val);
                                                                }
                                                            },
                                                            fallback(val) {
                                                                const el = document.createElement('textarea');
                                                                el.value = val;
                                                                el.setAttribute('readonly', '');
                                                                el.style.position = 'absolute';
                                                                el.style.left = '-9999px';
                                                                document.body.appendChild(el);
                                                                el.select();
                                                                try {
                                                                    document.execCommand('copy');
                                                                    this.copied = true;
                                                                    setTimeout(() => this.copied = false, 1800);
                                                                } catch (e) {}
                                                                document.body.removeChild(el);
                                                            }
                                                        }"
                                                        @click="copy('{{ $formats['hsl'] }}')"
                                                        class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[var(--radius-sm)] border border-[var(--border-color)] bg-[var(--bg-main)] hover:border-[var(--brand-primary)] text-[var(--text-main)] transition-all cursor-pointer font-mono text-xs select-none"
                                                        title="Copiar código HSL: {{ $formats['hsl'] }}">
                                                        <span x-show="!copied" class="inline-flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5 text-[var(--text-muted)] group-hover:text-[var(--brand-primary)] transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                            </svg>
                                                            <span class="font-semibold text-[10px] text-[var(--text-muted)]">HSL</span>
                                                            <span>{{ $formats['hsl'] }}</span>
                                                        </span>
                                                        <span x-show="copied" x-cloak class="inline-flex items-center gap-1 text-[var(--status-finished)] font-bold font-['Open_Sans']">
                                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span>COPIADO!</span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </td>

                                            <!-- Origem / Tipo -->
                                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                                @if (! $library->is_custom)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/10 text-indigo-400 font-['Open_Sans']">
                                                    Sistema
                                                </span>
                                                @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 font-['Open_Sans']">
                                                    Personalizada
                                                </span>
                                                @endif
                                            </td>

                                            <!-- Ações -->
                                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                                @if ($canManage)
                                                <div class="inline-flex items-center gap-1">
                                                    <button type="button"
                                                        wire:click="editLibrary({{ $library->id }})"
                                                        class="p-1.5 rounded-[var(--radius-sm)] border border-[var(--border-color)] hover:border-[var(--brand-primary)] text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-[var(--bg-main)] transition-all cursor-pointer"
                                                        title="Editar biblioteca">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    <button type="button"
                                                        wire:click="deleteLibrary({{ $library->id }})"
                                                        wire:confirm="Tem certeza de que deseja excluir a biblioteca '{{ $library->name }}'?"
                                                        class="p-1.5 rounded-[var(--radius-sm)] border border-[var(--border-color)] hover:border-[var(--status-dropped)] text-[var(--text-muted)] hover:text-[var(--status-dropped)] hover:bg-[var(--status-dropped)]/10 transition-all cursor-pointer"
                                                        title="Excluir biblioteca">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                @else
                                                <span class="inline-flex items-center gap-1 text-xs text-[var(--text-muted)] font-['Roboto']" title="Itens padrão do sistema não podem ser alterados por usuários comuns">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                    Bloqueado
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="py-8 text-center text-xs text-[var(--text-muted)]">
                                                Nenhuma biblioteca digital cadastrada encontrada.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    @endif

                    <!-- ============================================================== -->
                    <!-- SUBTAB 3: GÊNEROS -->
                    <!-- ============================================================== -->
                    @if ($catalogSubTab === 'genres')
                    <div class="space-y-6 pt-2">

                        @if (session()->has('genre_status'))
                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--status-finished)]/15 border border-[var(--status-finished)]/40 text-[var(--status-finished)] text-sm font-medium flex items-center gap-2.5 font-['Open_Sans']">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ session('genre_status') }}</span>
                        </div>
                        @endif

                        @if (session()->has('genre_error'))
                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--status-dropped)]/15 border border-[var(--status-dropped)]/40 text-[var(--status-dropped)] text-sm font-medium flex items-center gap-2.5 font-['Open_Sans']">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>{{ session('genre_error') }}</span>
                        </div>
                        @endif

                        <!-- Formulário de Edição Inline (se ativo) -->
                        @if ($editing_genre_id)
                        <div class="p-5 rounded-[var(--radius-md)] bg-[var(--bg-main)] border-2 border-[var(--brand-primary)] space-y-4 shadow-md">
                            <div class="flex items-center justify-between pb-2 border-b border-[var(--border-color)]">
                                <h3 class="text-sm font-bold font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar Gênero
                                </h3>
                                <button type="button" wire:click="cancelEditGenre" class="text-xs text-[var(--text-muted)] hover:text-[var(--text-main)] cursor-pointer transition-colors">
                                    Cancelar
                                </button>
                            </div>

                            <form wire:submit="updateGenre" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <div class="md:col-span-8">
                                    <label class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                        Nome do Gênero
                                    </label>
                                    <input type="text"
                                        wire:model="edit_genre_name"
                                        class="h-11 w-full px-3.5 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] text-sm font-['Roboto'] focus:outline-none focus:border-[var(--brand-primary)] box-border">
                                    @error('edit_genre_name')
                                    <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-4 flex items-center gap-2">
                                    <button type="submit"
                                        class="h-11 w-full inline-flex items-center justify-center px-4 rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white text-xs sm:text-sm font-bold font-['Open_Sans'] shadow-md hover:opacity-90 active:scale-95 transition-all cursor-pointer box-border">
                                        Salvar
                                    </button>
                                    <button type="button"
                                        wire:click="cancelEditGenre"
                                        class="h-11 inline-flex items-center justify-center px-4 rounded-[var(--radius-md)] border border-[var(--border-color)] text-[var(--text-muted)] hover:text-[var(--text-main)] text-xs sm:text-sm font-semibold font-['Open_Sans'] transition-all cursor-pointer box-border shrink-0">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                        @else
                        <!-- Inserção de Novo Gênero -->
                        <div class="p-5 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)] space-y-4">
                            <div>
                                <h3 class="text-sm font-bold font-['Ubuntu'] text-[var(--text-main)] flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Cadastrar Novo Gênero
                                </h3>
                                <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Adicione novas categorias ou subgêneros de jogos (ex: Soulslike, Cyberpunk, Sobrevivência...)</p>
                            </div>

                            <form wire:submit="createGenre" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                                <div class="md:col-span-8">
                                    <label for="new_genre_name" class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider mb-1.5 font-['Open_Sans']">
                                        Nome do Gênero
                                    </label>
                                    <input type="text"
                                        id="new_genre_name"
                                        wire:model="genre_name"
                                        placeholder="Ex: Soulslike, Tower Defense, Beat 'em Up..."
                                        class="h-11 w-full px-3.5 rounded-[var(--radius-md)] bg-[var(--bg-card)] border border-[var(--border-color)] text-[var(--text-main)] text-sm font-['Roboto'] placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--brand-primary)] box-border">
                                    @error('genre_name')
                                    <p class="mt-1 text-xs text-[var(--status-dropped)] font-medium font-['Roboto']">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-4">
                                    <button type="submit"
                                        class="h-11 w-full inline-flex items-center justify-center gap-2 px-5 rounded-[var(--radius-md)] bg-[var(--brand-primary)] text-white text-xs sm:text-sm font-bold font-['Open_Sans'] shadow-md hover:opacity-90 active:scale-[0.98] transition-all cursor-pointer box-border">
                                        <svg wire:loading.remove wire:target="createGenre" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        <svg wire:loading wire:target="createGenre" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                        <span>Adicionar Gênero</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Listagem dos Gêneros -->
                        <div class="space-y-3 pt-2">
                            <span class="block text-xs font-semibold text-[var(--text-main)] uppercase tracking-wider font-['Open_Sans']">
                                Gêneros Cadastrados ({{ $genres->count() }})
                            </span>

                            <div class="overflow-x-auto rounded-[var(--radius-md)] border border-[var(--border-color)]">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-[var(--bg-main)]/80 border-b border-[var(--border-color)] text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider font-['Open_Sans']">
                                            <th class="py-3 px-4">Nome do Gênero</th>
                                            <th class="py-3 px-4 text-center">Origem</th>
                                            <th class="py-3 px-4 text-right">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[var(--border-color)] font-['Roboto'] text-sm bg-[var(--bg-card)]">
                                        @forelse ($genres as $genre)
                                        @php
                                        $canManage = $genre->canBeManagedBy($user);
                                        @endphp
                                        <tr class="hover:bg-[var(--bg-main)]/40 transition-colors">
                                            <!-- Nome do gênero -->
                                            <td class="py-3 px-4 font-semibold text-[var(--text-main)] font-['Ubuntu']">
                                                {{ $genre->name }}
                                            </td>

                                            <!-- Origem / Tipo -->
                                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                                @if (! $genre->is_custom)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-500/10 text-indigo-400 font-['Open_Sans']">
                                                    Sistema
                                                </span>
                                                @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-400 font-['Open_Sans']">
                                                    Personalizado
                                                </span>
                                                @endif
                                            </td>

                                            <!-- Ações -->
                                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                                @if ($canManage)
                                                <div class="inline-flex items-center gap-1">
                                                    <button type="button"
                                                        wire:click="editGenre({{ $genre->id }})"
                                                        class="p-1.5 rounded-[var(--radius-sm)] border border-[var(--border-color)] hover:border-[var(--brand-primary)] text-[var(--text-muted)] hover:text-[var(--text-main)] hover:bg-[var(--bg-main)] transition-all cursor-pointer"
                                                        title="Editar gênero">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    <button type="button"
                                                        wire:click="deleteGenre({{ $genre->id }})"
                                                        wire:confirm="Tem certeza de que deseja excluir o gênero '{{ $genre->name }}'?"
                                                        class="p-1.5 rounded-[var(--radius-sm)] border border-[var(--border-color)] hover:border-[var(--status-dropped)] text-[var(--text-muted)] hover:text-[var(--status-dropped)] hover:bg-[var(--status-dropped)]/10 transition-all cursor-pointer"
                                                        title="Excluir gênero">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                @else
                                                <span class="inline-flex items-center gap-1 text-xs text-[var(--text-muted)] font-['Roboto']" title="Itens padrão do sistema não podem ser alterados por usuários comuns">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                    Bloqueado
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="py-8 text-center text-xs text-[var(--text-muted)]">
                                                Nenhum gênero cadastrado encontrado.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    @endif

                </div>

            </div>
            @endif

            {{-- ====================================================================== --}}
            {{-- TAB 3: Informações do Sistema / Métricas do Catálogo --}}
            {{-- ====================================================================== --}}
            @if ($tab === 'system')
            <div class="space-y-6">

                @if ($user->isAdmin())
                <!-- Header info & Diagnostics Refresh (Admin Only) -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 shadow-[var(--elevation-low)]">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[var(--border-color)] mb-5">
                        <div>
                            <h2 class="text-lg font-bold font-['Ubuntu'] text-[var(--text-main)]">Diagnóstico da Infraestrutura</h2>
                            <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Status dos serviços, engine de busca Elasticsearch e métricas do catálogo</p>
                        </div>
                        <button type="button"
                            wire:click="$refresh"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-[var(--radius-md)] border border-[var(--border-color)] text-xs font-semibold font-['Open_Sans'] text-[var(--text-main)] hover:bg-[var(--border-color)]/40 transition-all cursor-pointer">
                            <svg wire:loading.remove wire:target="$refresh" class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <svg wire:loading wire:target="$refresh" class="w-4 h-4 animate-spin text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span>Atualizar Diagnóstico</span>
                        </button>
                    </div>

                    <!-- Services Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        @if (!empty($diagnostics['database']))
                        <!-- PostgreSQL Card -->
                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="p-2 rounded bg-indigo-500/10 text-indigo-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-sm font-['Ubuntu'] text-[var(--text-main)]">Banco de Dados Relacional</h3>
                                        <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">PostgreSQL</p>
                                    </div>
                                </div>

                                @if ($diagnostics['database']['connected'])
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[var(--status-finished)]/15 text-[var(--status-finished)] font-['Open_Sans']">
                                    <span class="w-2 h-2 rounded-full bg-[var(--status-finished)] animate-pulse"></span>
                                    Conectado
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[var(--status-dropped)]/15 text-[var(--status-dropped)] font-['Open_Sans']">
                                    <span class="w-2 h-2 rounded-full bg-[var(--status-dropped)]"></span>
                                    Indisponível
                                </span>
                                @endif
                            </div>

                            <div class="text-xs font-['Roboto'] space-y-1.5 pt-1 text-[var(--text-muted)]">
                                <div class="flex justify-between">
                                    <span>Database:</span>
                                    <span class="font-mono text-[var(--text-main)] font-semibold">{{ $diagnostics['database']['database'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Host / Porta:</span>
                                    <span class="font-mono text-[var(--text-main)]">{{ $diagnostics['database']['host'] }}:{{ $diagnostics['database']['port'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Latência de Consulta:</span>
                                    <span class="font-mono text-[var(--text-main)]">{{ $diagnostics['database']['latency_ms'] }} ms</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Versão:</span>
                                    <span class="font-mono text-[var(--text-main)] truncate max-w-[200px]" title="{{ $diagnostics['database']['version'] }}">{{ $diagnostics['database']['version'] }}</span>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (!empty($diagnostics['elasticsearch']))
                        <!-- Elasticsearch Card -->
                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="p-2 rounded bg-amber-500/10 text-amber-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-sm font-['Ubuntu'] text-[var(--text-main)]">Engine de Busca</h3>
                                        <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Elasticsearch 8.x</p>
                                    </div>
                                </div>

                                @if ($diagnostics['elasticsearch']['available'])
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[var(--status-finished)]/15 text-[var(--status-finished)] font-['Open_Sans']">
                                    <span class="w-2 h-2 rounded-full bg-[var(--status-finished)] animate-pulse"></span>
                                    Online
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/15 text-amber-500 font-['Open_Sans']">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    Fallback Ativo
                                </span>
                                @endif
                            </div>

                            <div class="text-xs font-['Roboto'] space-y-1.5 pt-1 text-[var(--text-muted)]">
                                <div class="flex justify-between">
                                    <span>Host do Cluster:</span>
                                    <span class="font-mono text-[var(--text-main)] truncate max-w-[200px]">{{ $diagnostics['elasticsearch']['host'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Índice Principal:</span>
                                    <span class="font-mono text-[var(--text-main)] font-semibold">{{ $diagnostics['elasticsearch']['index'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Comportamento:</span>
                                    <span class="text-[var(--text-main)]">Edge n-gram & Autocomplete</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Status de Sincronização:</span>
                                    <span class="text-[var(--text-main)]">{{ $diagnostics['elasticsearch']['available'] ? 'Operacional' : 'Fallback no SQL' }}</span>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                @if (!empty($diagnostics['stack']))
                <!-- Section 3.2: Stack de Tecnologia & Ambiente (Admin Only) -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 shadow-[var(--elevation-low)] space-y-4">
                    <div class="pb-4 border-b border-[var(--border-color)]">
                        <h2 class="text-lg font-bold font-['Ubuntu'] text-[var(--text-main)]">Ambiente da Aplicação</h2>
                        <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Informações técnicas de execução e dependências fundamentais</p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)]">
                            <span class="block text-[11px] font-semibold text-[var(--text-muted)] uppercase font-['Open_Sans']">PHP Version</span>
                            <span class="font-bold font-mono text-base text-[var(--text-main)] mt-1 block">v{{ $diagnostics['stack']['php_version'] }}</span>
                        </div>

                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)]">
                            <span class="block text-[11px] font-semibold text-[var(--text-muted)] uppercase font-['Open_Sans']">Laravel Version</span>
                            <span class="font-bold font-mono text-base text-[var(--text-main)] mt-1 block">v{{ $diagnostics['stack']['laravel_version'] }}</span>
                        </div>

                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)]">
                            <span class="block text-[11px] font-semibold text-[var(--text-muted)] uppercase font-['Open_Sans']">Ambiente (APP_ENV)</span>
                            <span class="font-bold text-sm text-[var(--text-main)] mt-1 block capitalize font-['Roboto']">{{ $diagnostics['stack']['environment'] }}</span>
                        </div>

                        <div class="p-3.5 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)]">
                            <span class="block text-[11px] font-semibold text-[var(--text-muted)] uppercase font-['Open_Sans']">Session Driver</span>
                            <span class="font-bold font-mono text-sm text-[var(--text-main)] mt-1 block capitalize">{{ $diagnostics['stack']['session_driver'] }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Section 3.3: Métricas Globais do Catálogo (Admin) -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 shadow-[var(--elevation-low)] space-y-4">
                    <div class="pb-4 border-b border-[var(--border-color)]">
                        <h2 class="text-lg font-bold font-['Ubuntu'] text-[var(--text-main)]">Métricas Globais do Catálogo</h2>
                        <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Consolidação de dados armazenados no sistema e engajamento da plataforma</p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--brand-primary)] block">
                                {{ $diagnostics['metrics']['total_catalog_games'] }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Jogos no Catálogo
                            </span>
                        </div>

                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--status-playing)] block">
                                {{ $diagnostics['metrics']['user_library_games'] }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Na Minha Biblioteca
                            </span>
                        </div>

                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--text-main)] block">
                                {{ $diagnostics['metrics']['total_platforms'] }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Plataformas
                            </span>
                        </div>

                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--text-main)] block">
                                {{ $diagnostics['metrics']['total_libraries'] }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Bibliotecas Digitais
                            </span>
                        </div>

                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--text-main)] block">
                                {{ $diagnostics['metrics']['total_genres'] }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Gêneros
                            </span>
                        </div>

                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-indigo-400 block">
                                {{ $diagnostics['metrics']['total_users'] ?? 0 }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Usuários Registrados
                            </span>
                        </div>

                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-teal-400 block">
                                {{ $diagnostics['metrics']['total_user_games'] ?? 0 }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Vínculos no Catálogo
                            </span>
                        </div>
                    </div>
                </div>

                @else
                <!-- Non-Admin View: Métricas do Catálogo Apenas -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 shadow-[var(--elevation-low)] space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[var(--border-color)]">
                        <div>
                            <h2 class="text-lg font-bold font-['Ubuntu'] text-[var(--text-main)]">Métricas do Catálogo</h2>
                            <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Estatísticas dos jogos disponíveis e da sua coleção pessoal</p>
                        </div>
                        <button type="button"
                            wire:click="$refresh"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-[var(--radius-md)] border border-[var(--border-color)] text-xs font-semibold font-['Open_Sans'] text-[var(--text-main)] hover:bg-[var(--border-color)]/40 transition-all cursor-pointer">
                            <svg wire:loading.remove wire:target="$refresh" class="w-4 h-4 text-[var(--text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <svg wire:loading wire:target="$refresh" class="w-4 h-4 animate-spin text-[var(--brand-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span>Atualizar Métricas</span>
                        </button>
                    </div>

                    <!-- 5 Main Metrics Cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--brand-primary)] block">
                                {{ $diagnostics['metrics']['total_catalog_games'] }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Jogos no Catálogo
                            </span>
                        </div>

                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--status-playing)] block">
                                {{ $diagnostics['metrics']['user_library_games'] }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Na Minha Biblioteca
                            </span>
                        </div>

                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--text-main)] block">
                                {{ $diagnostics['metrics']['total_platforms'] }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Plataformas
                            </span>
                        </div>

                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--text-main)] block">
                                {{ $diagnostics['metrics']['total_libraries'] }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Bibliotecas Digitais
                            </span>
                        </div>

                        <div class="p-4 rounded-[var(--radius-md)] bg-[var(--bg-main)]/60 border border-[var(--border-color)] text-center col-span-2 sm:col-span-1">
                            <span class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] text-[var(--text-main)] block">
                                {{ $diagnostics['metrics']['total_genres'] }}
                            </span>
                            <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans'] mt-1 block">
                                Gêneros
                            </span>
                        </div>
                    </div>

                    <!-- Status Breakdown for User's Library -->
                    @if ($diagnostics['metrics']['user_library_games'] > 0)
                    <div class="pt-4 border-t border-[var(--border-color)]">
                        <h3 class="text-sm font-bold font-['Ubuntu'] text-[var(--text-main)] mb-3">Status da Minha Coleção</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="p-3 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)] flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[var(--status-playing)]"></span>
                                    <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans']">Jogando</span>
                                </div>
                                <span class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)]">{{ $diagnostics['metrics']['playing_games'] ?? 0 }}</span>
                            </div>

                            <div class="p-3 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)] flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[var(--status-finished)]"></span>
                                    <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans']">Finalizados</span>
                                </div>
                                <span class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)]">{{ $diagnostics['metrics']['finished_games'] ?? 0 }}</span>
                            </div>

                            <div class="p-3 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)] flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[var(--status-backlog)]"></span>
                                    <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans']">Backlog</span>
                                </div>
                                <span class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)]">{{ $diagnostics['metrics']['backlog_games'] ?? 0 }}</span>
                            </div>

                            <div class="p-3 rounded-[var(--radius-md)] bg-[var(--bg-main)]/50 border border-[var(--border-color)] flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[var(--status-dropped)]"></span>
                                    <span class="text-xs font-semibold text-[var(--text-muted)] font-['Open_Sans']">Desistidos</span>
                                </div>
                                <span class="text-base font-bold font-['Ubuntu'] text-[var(--text-main)]">{{ $diagnostics['metrics']['dropped_games'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

            </div>
            @endif

        </div>

    </div>

</div>