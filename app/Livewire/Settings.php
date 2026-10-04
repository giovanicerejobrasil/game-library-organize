<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\ThemeMode;
use App\Models\GameLibrary;
use App\Models\Genre;
use App\Models\Platform;
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

    // Catalog Management tab state
    #[Url(as: 'subtab')]
    public string $catalogSubTab = 'platforms'; // 'platforms', 'libraries', 'genres'

    // Plataformas
    public string $platform_name = '';

    public string $platform_color = '#182075';

    public ?int $editing_platform_id = null;

    public string $edit_platform_name = '';

    public string $edit_platform_color = '#182075';

    // Bibliotecas
    public string $library_name = '';

    public string $library_color = '#1D2C4B';

    public ?int $editing_library_id = null;

    public string $edit_library_name = '';

    public string $edit_library_color = '#1D2C4B';

    // Gêneros
    public string $genre_name = '';

    public ?int $editing_genre_id = null;

    public string $edit_genre_name = '';

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

        if (! in_array($this->tab, ['account', 'colors', 'catalog', 'system'], true)) {
            $this->tab = 'account';
        }

        if (! in_array($this->catalogSubTab, ['platforms', 'libraries', 'genres'], true)) {
            $this->catalogSubTab = 'platforms';
        }
    }

    /**
     * Altera a aba ativa e reseta os erros de validação
     */
    public function setTab(string $tab): void
    {
        if (in_array($tab, ['account', 'colors', 'catalog', 'system'], true)) {
            $this->tab = $tab;
            $this->resetValidation();
        }
    }

    /**
     * Altera a sub-aba de gerenciamento do catálogo
     */
    public function setCatalogSubTab(string $subTab): void
    {
        if (in_array($subTab, ['platforms', 'libraries', 'genres'], true)) {
            $this->catalogSubTab = $subTab;
            $this->resetValidation();
            $this->cancelEditPlatform();
            $this->cancelEditLibrary();
            $this->cancelEditGenre();
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

    // ==========================================
    // PLATAFORMAS ACTIONS
    // ==========================================
    public function createPlatform(SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'platform_name' => ['required', 'string', 'min:2', 'max:100'],
            'platform_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ], [
            'platform_name.required' => 'O nome da plataforma é obrigatório.',
            'platform_name.min' => 'O nome da plataforma deve ter pelo menos 2 caracteres.',
            'platform_color.required' => 'A cor da plataforma é obrigatória.',
            'platform_color.regex' => 'A cor deve ser um código hexadecimal válido (ex: #182075).',
        ]);

        $settingsService->createPlatform($user, $this->platform_name, $this->platform_color);

        $this->reset(['platform_name']);
        $this->platform_color = '#182075';

        session()->flash('platform_status', 'Plataforma cadastrada com sucesso!');
    }

    public function editPlatform(int $id): void
    {
        $platform = Platform::find($id);
        if (! $platform) {
            return;
        }

        $this->editing_platform_id = $platform->id;
        $this->edit_platform_name = $platform->name;
        $this->edit_platform_color = $platform->resolved_color;
    }

    public function cancelEditPlatform(): void
    {
        $this->reset(['editing_platform_id', 'edit_platform_name', 'edit_platform_color']);
    }

    public function updatePlatform(SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'edit_platform_name' => ['required', 'string', 'min:2', 'max:100'],
            'edit_platform_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ], [
            'edit_platform_name.required' => 'O nome da plataforma é obrigatório.',
            'edit_platform_name.min' => 'O nome da plataforma deve ter pelo menos 2 caracteres.',
            'edit_platform_color.required' => 'A cor da plataforma é obrigatória.',
            'edit_platform_color.regex' => 'A cor deve ser um código hexadecimal válido (ex: #182075).',
        ]);

        $platform = Platform::find($this->editing_platform_id);
        if (! $platform) {
            $this->cancelEditPlatform();

            return;
        }

        try {
            $settingsService->updatePlatform($user, $platform, $this->edit_platform_name, $this->edit_platform_color);
            $this->cancelEditPlatform();
            session()->flash('platform_status', 'Plataforma atualizada com sucesso!');
        } catch (\Throwable $e) {
            session()->flash('platform_error', $e->getMessage());
        }
    }

    public function deletePlatform(int $id, SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $platform = Platform::find($id);
        if (! $platform) {
            return;
        }

        try {
            $settingsService->deletePlatform($user, $platform);
            session()->flash('platform_status', 'Plataforma excluída com sucesso!');
        } catch (\Throwable $e) {
            session()->flash('platform_error', $e->getMessage());
        }
    }

    // ==========================================
    // BIBLIOTECAS DIGITAIS ACTIONS
    // ==========================================
    public function createLibrary(SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'library_name' => ['required', 'string', 'min:2', 'max:100'],
            'library_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ], [
            'library_name.required' => 'O nome da biblioteca é obrigatório.',
            'library_name.min' => 'O nome da biblioteca deve ter pelo menos 2 caracteres.',
            'library_color.required' => 'A cor da biblioteca é obrigatória.',
            'library_color.regex' => 'A cor deve ser um código hexadecimal válido (ex: #1D2C4B).',
        ]);

        $settingsService->createLibrary($user, $this->library_name, $this->library_color);

        $this->reset(['library_name']);
        $this->library_color = '#1D2C4B';

        session()->flash('library_status', 'Biblioteca digital para PC cadastrada com sucesso!');
    }

    public function editLibrary(int $id): void
    {
        $library = GameLibrary::find($id);
        if (! $library) {
            return;
        }

        $this->editing_library_id = $library->id;
        $this->edit_library_name = $library->name;
        $this->edit_library_color = $library->resolved_color;
    }

    public function cancelEditLibrary(): void
    {
        $this->reset(['editing_library_id', 'edit_library_name', 'edit_library_color']);
    }

    public function updateLibrary(SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'edit_library_name' => ['required', 'string', 'min:2', 'max:100'],
            'edit_library_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ], [
            'edit_library_name.required' => 'O nome da biblioteca é obrigatório.',
            'edit_library_name.min' => 'O nome da biblioteca deve ter pelo menos 2 caracteres.',
            'edit_library_color.required' => 'A cor da biblioteca é obrigatória.',
            'edit_library_color.regex' => 'A cor deve ser um código hexadecimal válido (ex: #1D2C4B).',
        ]);

        $library = GameLibrary::find($this->editing_library_id);
        if (! $library) {
            $this->cancelEditLibrary();

            return;
        }

        try {
            $settingsService->updateLibrary($user, $library, $this->edit_library_name, $this->edit_library_color);
            $this->cancelEditLibrary();
            session()->flash('library_status', 'Biblioteca atualizada com sucesso!');
        } catch (\Throwable $e) {
            session()->flash('library_error', $e->getMessage());
        }
    }

    public function deleteLibrary(int $id, SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $library = GameLibrary::find($id);
        if (! $library) {
            return;
        }

        try {
            $settingsService->deleteLibrary($user, $library);
            session()->flash('library_status', 'Biblioteca excluída com sucesso!');
        } catch (\Throwable $e) {
            session()->flash('library_error', $e->getMessage());
        }
    }

    // ==========================================
    // GÊNEROS ACTIONS
    // ==========================================
    public function createGenre(SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'genre_name' => ['required', 'string', 'min:2', 'max:100'],
        ], [
            'genre_name.required' => 'O nome do gênero é obrigatório.',
            'genre_name.min' => 'O nome do gênero deve ter pelo menos 2 caracteres.',
        ]);

        $settingsService->createGenre($user, $this->genre_name);

        $this->reset(['genre_name']);

        session()->flash('genre_status', 'Gênero cadastrado com sucesso!');
    }

    public function editGenre(int $id): void
    {
        $genre = Genre::find($id);
        if (! $genre) {
            return;
        }

        $this->editing_genre_id = $genre->id;
        $this->edit_genre_name = $genre->name;
    }

    public function cancelEditGenre(): void
    {
        $this->reset(['editing_genre_id', 'edit_genre_name']);
    }

    public function updateGenre(SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'edit_genre_name' => ['required', 'string', 'min:2', 'max:100'],
        ], [
            'edit_genre_name.required' => 'O nome do gênero é obrigatório.',
            'edit_genre_name.min' => 'O nome do gênero deve ter pelo menos 2 caracteres.',
        ]);

        $genre = Genre::find($this->editing_genre_id);
        if (! $genre) {
            $this->cancelEditGenre();

            return;
        }

        try {
            $settingsService->updateGenre($user, $genre, $this->edit_genre_name);
            $this->cancelEditGenre();
            session()->flash('genre_status', 'Gênero atualizado com sucesso!');
        } catch (\Throwable $e) {
            session()->flash('genre_error', $e->getMessage());
        }
    }

    public function deleteGenre(int $id, SettingsService $settingsService): void
    {
        /** @var User $user */
        $user = Auth::user();

        $genre = Genre::find($id);
        if (! $genre) {
            return;
        }

        try {
            $settingsService->deleteGenre($user, $genre);
            session()->flash('genre_status', 'Gênero excluído com sucesso!');
        } catch (\Throwable $e) {
            session()->flash('genre_error', $e->getMessage());
        }
    }

    public function render(SettingsService $settingsService): View
    {
        /** @var User $user */
        $user = Auth::user();

        $diagnostics = $settingsService->getSystemDiagnostics($user);
        $platforms = $settingsService->getPlatformsForUser($user);
        $libraries = $settingsService->getLibrariesForUser($user);
        $genres = $settingsService->getGenresForUser($user);

        return view('livewire.settings', [
            'user' => $user,
            'presets' => self::PRESETS,
            'diagnostics' => $diagnostics,
            'platforms' => $platforms,
            'libraries' => $libraries,
            'genres' => $genres,
        ])->layout('layouts.app', [
            'title' => 'Configurações',
        ]);
    }
}
