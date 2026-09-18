<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\AgeRating;
use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\Platform;
use App\Models\User;
use App\Services\Games\GameService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddGame extends Component
{
    use WithFileUploads;

    // Dados Globais do Jogo
    public string $title = '';

    public string $cover_image = '';

    public $cover_file = null;

    public string $background_image = '';

    public $background_file = null;

    public string $synopsis = '';

    public ?int $release_year = null;

    public string $developer = '';

    public string $publisher = '';

    public string $trailer_url = '';

    public bool $is_franchise = false;

    public string $franchise_name = '';

    public ?string $age_rating = null;

    /** @var array<int, string> */
    public array $selected_genres = [];

    public string $custom_genre = '';

    /** @var array<int, array{store: string, url: string}> */
    public array $purchase_links = [];

    /** @var array<int, int> */
    public array $selected_platforms = [];

    /** @var array<int, int> */
    public array $selected_libraries = [];

    // Dados de Progresso Pessoal do Usuário (UserGame)
    public string $status = 'backlog';

    public float|int|string $hours_played = 0;

    public ?float $rating = null;

    public string $review = '';

    // Catálogo existente / Sugestões
    public string $catalogSearch = '';

    public ?int $existing_game_id = null;

    public ?string $selectedGameTitle = null;

    public bool $isEditMode = false;

    public function mount(?Game $game = null): void
    {
        $this->status = GameStatus::Backlog->value;
        $this->purchase_links = [];

        if ($game && $game->exists) {
            $this->isEditMode = true;
            $this->selectCatalogGame($game->id);
        }
    }

    /**
     * Alterna a seleção de um gênero
     */
    public function toggleGenre(string $genre): void
    {
        $genre = trim($genre);
        if ($genre === '') {
            return;
        }

        if (in_array($genre, $this->selected_genres, true)) {
            $this->selected_genres = array_values(array_filter(
                $this->selected_genres,
                fn ($g) => $g !== $genre
            ));
        } else {
            $this->selected_genres[] = $genre;
        }
    }

    /**
     * Adiciona um gênero personalizado
     */
    public function addCustomGenre(): void
    {
        $custom = trim($this->custom_genre);
        if ($custom !== '' && ! in_array($custom, $this->selected_genres, true)) {
            $this->selected_genres[] = $custom;
            $this->custom_genre = '';
        }
    }

    /**
     * Adiciona uma nova linha para link de compra
     */
    public function addPurchaseLink(): void
    {
        $this->purchase_links[] = ['store' => '', 'url' => ''];
    }

    /**
     * Remove uma linha de link de compra
     */
    public function removePurchaseLink(int $index): void
    {
        unset($this->purchase_links[$index]);
        $this->purchase_links = array_values($this->purchase_links);
    }

    /**
     * Define a avaliação por estrelas (0 a 5)
     */
    public function setRating(float $value): void
    {
        $this->rating = ($this->rating === $value) ? null : $value;
    }

    /**
     * Verifica se a plataforma PC está entre as plataformas selecionadas
     */
    public function isPcSelected(): bool
    {
        if (empty($this->selected_platforms)) {
            return false;
        }

        $platformIds = array_map('intval', (array) $this->selected_platforms);

        return Platform::whereIn('id', $platformIds)
            ->where(function ($query) {
                $query->where('slug', 'pc')
                    ->orWhereRaw('LOWER(name) = ?', ['pc']);
            })
            ->exists();
    }

    /**
     * Hook chamado automaticamente pelo Livewire quando selected_platforms for alterado
     */
    public function updatedSelectedPlatforms(): void
    {
        if (! $this->isPcSelected()) {
            $this->selected_libraries = [];
        }
    }

    /**
     * Adiciona a plataforma PC à lista de plataformas selecionadas
     */
    public function selectPcPlatform(): void
    {
        $pcPlatform = Platform::where('slug', 'pc')
            ->orWhereRaw('LOWER(name) = ?', ['pc'])
            ->first();

        if ($pcPlatform) {
            $current = array_map('intval', (array) $this->selected_platforms);
            if (! in_array($pcPlatform->id, $current, true)) {
                $this->selected_platforms[] = $pcPlatform->id;
                $this->updatedSelectedPlatforms();
            }
        }
    }

    /**
     * Seleciona um jogo existente do catálogo para preencher os dados
     */
    public function selectCatalogGame(int $gameId): void
    {
        $game = Game::with(['platforms', 'libraries'])->find($gameId);
        if (! $game) {
            return;
        }

        $this->existing_game_id = $game->id;
        $this->selectedGameTitle = $game->title;
        $this->title = $game->title;
        $this->cover_image = $game->cover_image ?? '';
        $this->background_image = $game->background_image ?? '';
        $this->synopsis = $game->synopsis ?? '';
        $this->release_year = $game->release_year;
        $this->developer = $game->developer ?? '';
        $this->publisher = $game->publisher ?? '';
        $this->trailer_url = $game->trailer_url ?? '';
        $this->is_franchise = (bool) $game->is_franchise;
        $this->franchise_name = $game->franchise_name ?? '';
        $this->age_rating = $game->age_rating instanceof AgeRating
            ? $game->age_rating->value
            : ($game->age_rating ?? '');
        $this->selected_genres = is_array($game->genre) ? $game->genre : [];
        $this->selected_platforms = $game->platforms->pluck('id')->all();
        $this->selected_libraries = $this->isPcSelected()
            ? $game->libraries->pluck('id')->all()
            : [];

        $this->purchase_links = [];
        if (is_array($game->purchase_links)) {
            foreach ($game->purchase_links as $store => $url) {
                $this->purchase_links[] = [
                    'store' => (string) $store,
                    'url' => (string) $url,
                ];
            }
        }

        // Se o usuário já possuir registro deste jogo, carrega as informações pessoais
        /** @var User $user */
        $user = Auth::user();
        $userGame = $game->userGames()->where('user_id', $user->id)->first();
        if ($userGame) {
            $this->status = $userGame->status->value;
            $this->hours_played = (float) $userGame->hours_played;
            $this->rating = $userGame->rating !== null ? (float) $userGame->rating : null;
            $this->review = $userGame->review ?? '';
        }

        $this->catalogSearch = '';
    }

    /**
     * Limpa a seleção de jogo do catálogo e limpa todas as informações preenchidas
     */
    public function clearCatalogSelection(): void
    {
        $this->existing_game_id = null;
        $this->selectedGameTitle = null;
        $this->catalogSearch = '';

        // Limpa todos os campos globais do formulário
        $this->title = '';
        $this->cover_image = '';
        $this->cover_file = null;
        $this->background_image = '';
        $this->background_file = null;
        $this->synopsis = '';
        $this->release_year = null;
        $this->developer = '';
        $this->publisher = '';
        $this->trailer_url = '';
        $this->is_franchise = false;
        $this->franchise_name = '';
        $this->age_rating = null;
        $this->selected_genres = [];
        $this->custom_genre = '';
        $this->purchase_links = [];
        $this->selected_platforms = [];
        $this->selected_libraries = [];

        // Reseta dados de experiência pessoal
        $this->status = GameStatus::Backlog->value;
        $this->hours_played = 0;
        $this->rating = null;
        $this->review = '';

        $this->resetValidation();
    }

    /**
     * Salva o jogo e o progresso do usuário
     */
    public function save(GameService $gameService)
    {
        if ($this->age_rating === '') {
            $this->age_rating = null;
        }

        $rules = [
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'release_year' => ['nullable', 'integer', 'min:1970', 'max:2035'],
            'developer' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'trailer_url' => ['nullable', 'url', 'max:500'],
            'cover_image' => ['nullable', 'url', 'max:500'],
            'cover_file' => ['nullable', 'image', 'max:5120'],
            'background_image' => ['nullable', 'url', 'max:500'],
            'background_file' => ['nullable', 'image', 'max:8192'],
            'synopsis' => ['nullable', 'string', 'max:5000'],
            'is_franchise' => ['boolean'],
            'franchise_name' => ['nullable', 'string', 'max:255'],
            'age_rating' => ['nullable', Rule::enum(AgeRating::class)],
            'selected_genres' => ['nullable', 'array'],
            'selected_platforms' => ['nullable', 'array'],
            'selected_libraries' => ['nullable', 'array'],
            'purchase_links.*.store' => ['nullable', 'string', 'max:100'],
            'purchase_links.*.url' => ['nullable', 'url', 'max:500'],
            'status' => [$this->existing_game_id ? 'required' : 'nullable', Rule::enum(GameStatus::class)],
            'hours_played' => ['nullable', 'numeric', 'min:0', 'max:99999.99'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5.0'],
            'review' => ['nullable', 'string', 'max:10000'],
        ];

        if (empty($this->status)) {
            $this->status = GameStatus::Backlog->value;
        }

        $this->validate($rules, [
            'title.required' => 'O título do jogo é obrigatório.',
            'title.min' => 'O título deve ter no mínimo 2 caracteres.',
            'release_year.integer' => 'O ano de lançamento deve ser um número inteiro.',
            'release_year.min' => 'Ano de lançamento inválido.',
            'release_year.max' => 'Ano de lançamento inválido.',
            'trailer_url.url' => 'O trailer deve conter uma URL válida.',
            'cover_image.url' => 'A capa deve ser uma URL válida.',
            'cover_file.image' => 'O arquivo da capa deve ser uma imagem válida.',
            'background_image.url' => 'O background deve ser uma URL válida.',
            'background_file.image' => 'O arquivo de background deve ser uma imagem válida.',
            'status.required' => 'Selecione um status para o jogo na sua biblioteca.',
            'hours_played.numeric' => 'As horas jogadas devem ser numéricas.',
            'hours_played.min' => 'As horas jogadas não podem ser negativas.',
            'rating.min' => 'A avaliação deve ser de no mínimo 0 estrelas.',
            'rating.max' => 'A avaliação deve ser de no máximo 5 estrelas.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (! $this->isPcSelected()) {
            $this->selected_libraries = [];
        }

        // Formata links de compra em array associativo
        $formattedPurchaseLinks = [];
        foreach ($this->purchase_links as $link) {
            $store = trim($link['store'] ?? '');
            $url = trim($link['url'] ?? '');
            if ($store !== '' && $url !== '') {
                $formattedPurchaseLinks[$store] = $url;
            }
        }

        $gameData = [
            'existing_game_id' => $this->existing_game_id,
            'title' => $this->title,
            'cover_image' => $this->cover_image,
            'background_image' => $this->background_image,
            'synopsis' => $this->synopsis,
            'release_year' => $this->release_year,
            'developer' => $this->developer,
            'publisher' => $this->publisher,
            'trailer_url' => $this->trailer_url,
            'is_franchise' => $this->is_franchise,
            'franchise_name' => $this->is_franchise ? $this->franchise_name : null,
            'age_rating' => $this->age_rating,
            'genre' => $this->selected_genres,
            'purchase_links' => ! empty($formattedPurchaseLinks) ? $formattedPurchaseLinks : null,
            'platforms' => $this->selected_platforms,
            'libraries' => $this->selected_libraries,
        ];

        $userGameData = [
            'status' => $this->status,
            'hours_played' => (float) $this->hours_played,
            'rating' => $this->rating,
            'review' => $this->review,
        ];

        $userGame = $gameService->createOrUpdateGameForUser(
            $user,
            $gameData,
            $userGameData,
            $this->cover_file,
            $this->background_file
        );

        if ($this->isEditMode) {
            session()->flash('status', 'Informações do jogo atualizadas com sucesso!');
            $targetSlug = $userGame->game?->slug ?? Game::find($this->existing_game_id)?->slug ?? (string) $this->existing_game_id;

            return $this->redirect(route('games.show', $targetSlug), navigate: true);
        }

        session()->flash('status', 'Jogo adicionado à sua biblioteca com sucesso!');

        return $this->redirect(route('dashboard'), navigate: true);
    }

    public function render(GameService $gameService): View
    {
        $userId = (int) Auth::id();

        $catalogResults = $this->catalogSearch !== ''
            ? $gameService->searchCatalog($this->catalogSearch, 6)
            : collect();

        $availablePlatforms = $gameService->getAvailablePlatformsForUser($userId);
        $availableLibraries = $gameService->getAvailableLibrariesForUser($userId);
        $availableGenres = $gameService->getAvailableGenres();

        $pageTitle = $this->isEditMode
            ? ($this->title !== '' ? "Editar Jogo: {$this->title}" : 'Editar Informações do Jogo')
            : 'Adicionar Jogo à Biblioteca';

        return view('livewire.add-game', [
            'catalogResults' => $catalogResults,
            'availablePlatforms' => $availablePlatforms,
            'availableLibraries' => $availableLibraries,
            'availableGenres' => $availableGenres,
            'statuses' => GameStatus::cases(),
            'isPcSelected' => $this->isPcSelected(),
            'isEditMode' => $this->isEditMode,
        ])->layout('layouts.app', [
            'title' => $pageTitle,
        ]);
    }
}
