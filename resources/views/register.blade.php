<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<x-head :includeLivewire="true" title="{{ $data->title ?? 'Criar Conta' }}" />

<body class="antialiased min-h-screen flex flex-col font-sans bg-[var(--bg-main)] text-[var(--text-main)] selection:bg-[var(--brand-primary)] selection:text-white transition-colors duration-300">
    <!-- Header Component -->
    <x-header variant="guest" />

    <!-- Main Content Area -->
    <main class="flex-1 flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Retro Glow Ambient Effect -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[450px] bg-[var(--brand-primary)]/20 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="w-full max-w-md my-4">
            <!-- Register Card Container -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 sm:p-8 shadow-2xl shadow-black/40 relative backdrop-blur-sm transition-all duration-300">

                <!-- Card Header -->
                <div class="text-center mb-8">
                    <img src="{{ asset('images/logo/logo-2.png') }}" alt="Game Library Organize Logo" class="mx-auto w-[90px] mb-4">

                    <h1 class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] tracking-tight text-[var(--text-main)]">
                        Crie sua Conta
                    </h1>
                    <p class="mt-2 text-sm text-[var(--text-muted)] font-['Roboto']">
                        Comece a organizar sua biblioteca unificada de jogos agora mesmo.
                    </p>
                </div>

                <!-- Validation Alerts -->
                @if ($errors->any())
                <div class="mb-6 p-4 rounded-[var(--radius-md)] bg-[var(--status-dropped)]/15 border border-[var(--status-dropped)]/40 text-[var(--text-main)] text-sm font-['Roboto']">
                    <div class="flex items-center gap-2 font-semibold text-[var(--status-dropped)] mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Ops! Verifique as informações:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs text-[var(--text-muted)] space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Register Form -->
                <form action="{{ route('attemptSignUp') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-main)] font-['Open_Sans'] mb-2">
                            Nome Completo
                        </label>
                        <div class="relative rounded-[var(--radius-md)]">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[var(--text-muted)]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                placeholder="Seu nome ou nickname"
                                required
                                autofocus
                                class="w-full pl-10 pr-4 py-3 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary)]/30 transition-all font-['Roboto']" />
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-main)] font-['Open_Sans'] mb-2">
                            E-mail
                        </label>
                        <div class="relative rounded-[var(--radius-md)]">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[var(--text-muted)]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path>
                                </svg>
                            </div>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                placeholder="seu@email.com"
                                required
                                class="w-full pl-10 pr-4 py-3 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary)]/30 transition-all font-['Roboto']" />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <livewire:password-strength />

                    <!-- Password Confirmation Field -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-main)] font-['Open_Sans'] mb-2">
                            Confirmar Senha
                        </label>
                        <div class="relative rounded-[var(--radius-md)]">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[var(--text-muted)]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                placeholder="••••••••••"
                                required
                                minlength="8"
                                class="w-full pl-10 pr-11 py-3 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary)]/30 transition-all font-['Roboto']" />
                            <!-- Toggle Visibility Button -->
                            <button
                                type="button"
                                onclick="window.togglePasswordVisibility(this)"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[var(--text-muted)] hover:text-[var(--text-main)] focus:outline-none cursor-pointer"
                                title="Mostrar / Ocultar Senha"
                                aria-label="Mostrar ou ocultar confirmação de senha">
                                <!-- Eye Open Icon -->
                                <svg class="eye-open w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <!-- Eye Closed Icon -->
                                <svg class="eye-closed w-4.5 h-4.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Terms of Service Checkbox -->
                    <div class="pt-1">
                        <label class="flex items-start gap-2.5 text-xs font-['Roboto'] text-[var(--text-muted)] cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="terms"
                                id="terms"
                                required
                                class="mt-0.5 w-4 h-4 rounded-[var(--radius-sm)] border-[var(--border-color)] bg-[var(--bg-main)] text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]/30 focus:ring-offset-0 focus:ring-1 cursor-pointer" />
                            <span>
                                Li e concordo com os
                                <a href="#" class="text-[var(--text-main)] font-medium underline hover:text-[var(--brand-primary)] transition-colors">Termos de Uso</a>
                                e a
                                <a href="#" class="text-[var(--text-main)] font-medium underline hover:text-[var(--brand-primary)] transition-colors">Política de Privacidade</a>.
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full py-3.5 px-4 bg-[var(--brand-primary)] hover:opacity-95 text-white font-semibold font-['Open_Sans'] rounded-[var(--radius-md)] shadow-lg shadow-[var(--brand-primary)]/25 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2 text-sm mt-6 cursor-pointer">
                        <span>Criar Minha Conta</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>

                <!-- Divider & Login Link -->
                <div class="mt-8 pt-6 border-t border-[var(--border-color)] text-center">
                    <p class="text-xs text-[var(--text-muted)] font-['Roboto']">
                        Já possui uma conta?
                        <a href="{{ route('login') }}" class="font-semibold text-[var(--text-main)] hover:text-[var(--brand-primary)] font-['Open_Sans'] transition-colors ml-1">
                            Acessar minha conta
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer Component -->
    <x-footer variant="landing" />
</body>

</html>