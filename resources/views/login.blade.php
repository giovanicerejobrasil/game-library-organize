<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<x-head title="Acessar Conta" />

<body class="antialiased min-h-screen flex flex-col font-sans bg-[var(--bg-main)] text-[var(--text-main)] selection:bg-[var(--brand-primary)] selection:text-white transition-colors duration-300">
    <!-- Header Component -->
    <x-header variant="guest" />

    <!-- Main Content Area -->
    <main class="flex-1 flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Retro Glow Ambient Effect -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[350px] bg-[var(--brand-primary)]/20 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="w-full max-w-md">
            <!-- Login Card Container -->
            <div class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius-lg)] p-6 sm:p-8 shadow-2xl shadow-black/40 relative backdrop-blur-sm transition-all duration-300">

                <!-- Card Header -->
                <div class="text-center mb-8">
                    <img src="{{ asset('images/logo/logo-2.png') }}" alt="Game Library Organize Logo" class="mx-auto w-[100px] mb-5">

                    <h1 class="text-2xl sm:text-3xl font-bold font-['Ubuntu'] tracking-tight text-[var(--text-main)]">
                        Bem-vindo de volta!
                    </h1>
                    <p class="mt-2 text-sm text-[var(--text-muted)] font-['Roboto']">
                        Acesse sua biblioteca para gerenciar seus jogos.
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

                <!-- Login Form -->
                <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                    @csrf

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
                                autofocus
                                class="w-full pl-10 pr-4 py-3 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary)]/30 transition-all font-['Roboto']" />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-main)] font-['Open_Sans']">
                                Senha
                            </label>
                            <a href="#" class="text-xs text-[var(--text-muted)] hover:text-[var(--brand-primary)] font-['Open_Sans'] transition-colors">
                                Esqueceu a senha?
                            </a>
                        </div>
                        <div class="relative rounded-[var(--radius-md)]">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[var(--text-muted)]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="••••••••"
                                required
                                class="w-full pl-10 pr-11 py-3 bg-[var(--bg-main)] border border-[var(--border-color)] rounded-[var(--radius-md)] text-sm text-[var(--text-main)] placeholder-[var(--text-muted)]/60 focus:outline-none focus:border-[var(--brand-primary)] focus:ring-2 focus:ring-[var(--brand-primary)]/30 transition-all font-['Roboto']" />
                            <!-- Toggle Visibility Button -->
                            <button
                                type="button"
                                onclick="window.togglePasswordVisibility(this)"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[var(--text-muted)] hover:text-[var(--text-main)] focus:outline-none cursor-pointer"
                                title="Mostrar / Ocultar Senha"
                                aria-label="Mostrar ou ocultar senha">
                                <!-- Eye Open Icon (Visible initially when type=password) -->
                                <svg class="eye-open w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <!-- Eye Closed Icon (Hidden initially) -->
                                <svg class="eye-closed w-4.5 h-4.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 text-xs font-['Roboto'] text-[var(--text-muted)] cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                                class="w-4 h-4 rounded-[var(--radius-sm)] border-[var(--border-color)] bg-[var(--bg-main)] text-[var(--brand-primary)] focus:ring-[var(--brand-primary)]/30 focus:ring-offset-0 focus:ring-1 cursor-pointer" />
                            <span>Lembrar-me neste dispositivo</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full py-3.5 px-4 bg-[var(--brand-primary)] hover:opacity-95 text-white font-semibold font-['Open_Sans'] rounded-[var(--radius-md)] shadow-lg shadow-[var(--brand-primary)]/25 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2 text-sm mt-6 cursor-pointer">
                        <span>Entrar no Sistema</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>

                <!-- Divider & Register Link -->
                <div class="mt-8 pt-6 border-t border-[var(--border-color)] text-center">
                    <p class="text-xs text-[var(--text-muted)] font-['Roboto']">
                        Ainda não tem uma conta?
                        <a href="#" class="font-semibold text-[var(--text-main)] hover:text-[var(--brand-primary)] font-['Open_Sans'] transition-colors ml-1">
                            Cadastre-se gratuitamente
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