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

                    <!-- Menu 3: Informações do Sistema -->
                    <button type="button"
                        wire:click="setTab('system')"
                        class="w-full flex items-center gap-3 px-3.5 py-3 rounded-[var(--radius-md)] text-left text-sm font-semibold transition-all cursor-pointer {{ $tab === 'system' ? 'bg-[var(--brand-primary)] text-white shadow-md' : 'text-[var(--text-main)] hover:bg-[var(--border-color)]/40' }}">
                        <svg class="w-5 h-5 shrink-0 {{ $tab === 'system' ? 'text-white' : 'text-[var(--text-muted)]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                        <div>
                            <span class="block leading-tight">Informações do Sistema</span>
                            <span class="block text-xs font-normal {{ $tab === 'system' ? 'text-white/80' : 'text-[var(--text-muted)]' }}">Infraestrutura e diagnósticos</span>
                        </div>
                    </button>

                </nav>

                <!-- Footer tip in sidebar -->
                <div class="pt-3 border-t border-[var(--border-color)] text-xs text-[var(--text-muted)] font-['Roboto'] flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[var(--status-finished)] inline-block"></span>
                    <span>Stack: PostgreSQL + Elasticsearch</span>
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
                                    <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg class="eye-closed w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
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
                                        <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg class="eye-closed w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
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
                                        <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg class="eye-closed w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
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
            <!-- TAB 3: Informações do Sistema -->
            <!-- ====================================================================== -->
            @if ($tab === 'system')
            <div class="space-y-6">

                <!-- Header info & Diagnostics Refresh -->
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

                    </div>
                </div>

                <!-- Section 3.2: Stack de Tecnologia & Ambiente -->
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

                <!-- Section 3.3: Métricas do Catálogo & Biblioteca -->
                <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 shadow-[var(--elevation-low)] space-y-4">
                    <div class="pb-4 border-b border-[var(--border-color)]">
                        <h2 class="text-lg font-bold font-['Ubuntu'] text-[var(--text-main)]">Métricas do Catálogo</h2>
                        <p class="text-xs font-['Roboto'] text-[var(--text-muted)]">Consolidação de dados armazenados no sistema</p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
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
                    </div>
                </div>

            </div>
            @endif

        </div>

    </div>

</div>
