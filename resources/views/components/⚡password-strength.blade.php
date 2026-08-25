<?php

use Livewire\Component;

new class extends Component
{
    public string $password = '';

    public int $score = 0;

    public string $segmentColor = 'bg-[var(--border-color)]';

    public function updatedPassword(): void
    {
        $this->score = 0;

        if (preg_match('/[A-Z]/', $this->password)) {
            $this->score += 1;
        }

        if (preg_match('/[a-z]/', $this->password)) {
            $this->score += 1;
        }

        if (preg_match('/\d/', $this->password)) {
            $this->score += 1;
        }

        if (preg_match('/[^A-Za-z0-9]/', $this->password)) {
            $this->score += 1;
        }

        if (strlen($this->password) >= 8) {
            $this->score += 1;
        }

        $this->segmentColor = $this->verifyColor();
    }

    private function verifyColor(): string
    {
        return match (true) {
            $this->score >= 1 && $this->score <= 2 => 'bg-[var(--status-dropped)]',
            $this->score >= 3 && $this->score <= 4 => 'bg-[var(--status-backlog)]',
            $this->score >= 5 => 'bg-[var(--status-finished)]',
            default => 'bg-[var(--border-color)]',
        };
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-2">
        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-[var(--text-main)] font-['Open_Sans']">
            Senha
        </label>
        <div class="flex items-center gap-2">
            <span class="text-[11px] text-[var(--text-muted)] font-['Roboto']">
                Mínimo de 8 caracteres
            </span>
        </div>
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
            wire:model.live.debounce.150ms="password"
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
            aria-label="Mostrar ou ocultar senha">
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

    <!-- Barra de 5 Segmentos de Força da Senha -->
    <div class="mt-3 space-y-1.5">
        <div class="grid grid-cols-5 gap-1.5">
            @for ($i = 1; $i <= 5; $i++)
                <div class="h-1.5 rounded-[var(--radius-sm)] transition-all duration-300
                    @if ($i <= $this->score)
                        {{ $this->segmentColor }} shadow-sm
                    @else
                        bg-[var(--border-color)] opacity-60
                    @endif
                ">
        </div>
        @endfor
    </div>

    <!-- Rótulos Inferiores -->
    <div class="flex justify-between text-[10px] text-[var(--text-muted)] font-['Roboto']">
        <span>Muito Fraco</span>
        <span>Muito Forte</span>
    </div>
</div>
</div>