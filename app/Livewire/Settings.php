<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\ThemeMode;
use App\Models\User;
use App\Services\Settings\SettingsService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

class Settings extends Component
{
    #[Url(as: 'tab')]
    public string $tab = 'account';

    // Account tab state
    public string $name = '';

    public string $email = '';

    public string $current_password = '';

    public string $new_password = '';

    public string $new_password_confirmation = '';

    // Colors tab state
    public string $theme = 'dark';

    public string $brand_primary = '#182075';

    public string $brand_secondary = '#751919';

    /**
     * Color presets for quick selection
     *
     * @var array<int, array{name: string, primary: string, secondary: string, description: string}>
     */
    public const PRESETS = [
        [
            'name' => 'Clássico GLO',
            'primary' => '#182075',
            'secondary' => '#751919',
            'description' => 'Azul profundo e carmesim do design original',
        ],
        [
            'name' => 'Cyberpunk Neon',
            'primary' => '#00f0ff',
            'secondary' => '#ff003c',
            'description' => 'Ciano elétrico com magenta de alto contraste',
        ],
        [
            'name' => 'Emerald Arcade',
            'primary' => '#059669',
            'secondary' => '#dc2626',
            'description' => 'Verde esmeralda clássico e vermelho arcade',
        ],
        [
            'name' => 'Synthwave 80s',
            'primary' => '#8b5cf6',
            'secondary' => '#ec4899',
            'description' => 'Violeta futurista e rosa neon oitentista',
        ],
        [
            'name' => 'Midnight Ocean',
            'primary' => '#0284c7',
            'secondary' => '#f97316',
            'description' => 'Azul oceano profundo com acentos âmbar/laranja',
        ],
    ];

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->theme = $user->theme instanceof ThemeMode ? $user->theme->value : (string) ($user->theme ?? 'dark');
        $this->brand_primary = $user->brand_primary ?? SettingsService::DEFAULT_BRAND_PRIMARY;
        $this->brand_secondary = $user->brand_secondary ?? SettingsService::DEFAULT_BRAND_SECONDARY;

        if (! in_array($this->tab, ['account', 'colors', 'system'], true)) {
            $this->tab = 'account';
        }
    }

    /**
     * Altera a aba ativa e reseta os erros de validação
     */
    public function setTab(string $tab): void
    {
        if (in_array($tab, ['account', 'colors', 'system'], true)) {
            $this->tab = $tab;
            $this->resetValidation();
        }
    }

    /**
     * Atualiza dados de perfil do usuário (nome e email)
     */
    public function updateProfile(SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ], [
            'name.required' => 'O nome é obrigatório.',
            'name.min' => 'O nome deve ter no mínimo 2 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está sendo utilizado por outra conta.',
        ]);

        $settingsService->updateProfile($user, [
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('profile_status', 'Informações da conta atualizadas com sucesso!');
    }

    /**
     * Altera a senha do usuário
     */
    public function updatePassword(SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'A senha atual é obrigatória.',
            'new_password.required' => 'A nova senha é obrigatória.',
            'new_password.min' => 'A nova senha deve possuir pelo menos 8 caracteres.',
            'new_password.confirmed' => 'A confirmação da nova senha não confere.',
        ]);

        $settingsService->updatePassword($user, $this->current_password, $this->new_password);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('password_status', 'Senha alterada com sucesso!');
    }

    /**
     * Aplica uma paleta pré-definida de cores
     */
    public function applyPreset(string $primary, string $secondary): void
    {
        $this->brand_primary = $primary;
        $this->brand_secondary = $secondary;

        $this->dispatch('glo-colors-preview', primary: $primary, secondary: $secondary);
    }

    /**
     * Notifica alteração da cor primária para preview em tempo real
     */
    public function updatedBrandPrimary(): void
    {
        $this->dispatch('glo-colors-preview', primary: $this->brand_primary, secondary: $this->brand_secondary);
    }

    /**
     * Notifica alteração da cor secundária para preview em tempo real
     */
    public function updatedBrandSecondary(): void
    {
        $this->dispatch('glo-colors-preview', primary: $this->brand_primary, secondary: $this->brand_secondary);
    }

    /**
     * Persiste as preferências de cores e tema
     */
    public function saveColors(SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'theme' => ['required', Rule::enum(ThemeMode::class)],
            'brand_primary' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'brand_secondary' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ], [
            'theme.required' => 'Selecione um modo de tema.',
            'brand_primary.regex' => 'A cor primária deve ser um código hexadecimal válido (ex: #182075).',
            'brand_secondary.regex' => 'A cor secundária deve ser um código hexadecimal válido (ex: #751919).',
        ]);

        $settingsService->updateThemeSettings($user, $this->theme, $this->brand_primary, $this->brand_secondary);

        $this->dispatch('glo-colors-applied',
            theme: $this->theme,
            primary: $this->brand_primary,
            secondary: $this->brand_secondary
        );

        session()->flash('colors_status', 'Preferências de cores e tema salvas com sucesso!');
    }

    /**
     * Restaura as cores e tema padrão do sistema
     */
    public function resetColors(SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $settingsService->resetThemeSettings($user);

        $this->theme = ThemeMode::Dark->value;
        $this->brand_primary = SettingsService::DEFAULT_BRAND_PRIMARY;
        $this->brand_secondary = SettingsService::DEFAULT_BRAND_SECONDARY;

        $this->dispatch('glo-colors-applied',
            theme: $this->theme,
            primary: $this->brand_primary,
            secondary: $this->brand_secondary
        );

        session()->flash('colors_status', 'Cores e tema restaurados para os padrões do sistema!');
    }

    public function render(SettingsService $settingsService): View
    {
        /** @var User $user */
        $user = Auth::user();

        $diagnostics = $settingsService->getSystemDiagnostics($user);

        return view('livewire.settings', [
            'user' => $user,
            'presets' => self::PRESETS,
            'diagnostics' => $diagnostics,
        ])->layout('layouts.app', [
            'title' => 'Configurações',
        ]);
    }
}
