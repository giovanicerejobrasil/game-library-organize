<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Services\Dashboard\DashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public string $search = '';

    public string $platform = '';

    public string $library = '';

    public string $status = '';

    public string $genre = '';

    public string $year = '';

    public string $sortBy = 'recent';

    public string $scope = 'my_library';

    public string $viewMode = 'grid';

    // Estado do Modal de Edição Rápida
    public bool $showEditModal = false;

    public ?int $editingGameId = null;

    public string $editingGameTitle = '';

    public string $editingCoverImage = '';

    public string $editStatus = 'backlog';

    public float $editHours = 0.0;

    public float $editRating = 0.0;

    public string $editReview = '';

    public string $feedbackMessage = '';

    /**
     * Reseta a paginação ao atualizar filtros de busca
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPlatform(): void
    {
        $this->resetPage();
    }

    public function updatingLibrary(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingGenre(): void
    {
        $this->resetPage();
    }

    public function updatingYear(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function updatingScope(): void
    {
        $this->resetPage();
    }

    /**
     * Limpa todos os filtros ativos
     */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->platform = '';
        $this->library = '';
        $this->status = '';
        $this->genre = '';
        $this->year = '';
        $this->sortBy = 'recent';
        $this->resetPage();
    }

    /**
     * Conta quantos filtros estão ativos
     */
    public function getActiveFiltersCountProperty(): int
    {
        $count = 0;
        if ($this->search !== '') {
            $count++;
        }
        if ($this->platform !== '') {
            $count++;
        }
        if ($this->library !== '') {
            $count++;
        }
        if ($this->status !== '') {
            $count++;
        }
        if ($this->genre !== '') {
            $count++;
        }
        if ($this->year !== '') {
            $count++;
        }
        if ($this->sortBy !== 'recent') {
            $count++;
        }

        return $count;
    }

    /**
     * Abre o modal de edição rápida de progresso do jogo
     */
    public function openEditModal(int $gameId): void
    {
        $userId = (int) Auth::id();
        $game = Game::with(['userGames' => fn ($q) => $q->where('user_id', $userId)])->find($gameId);

        if (! $game) {
            return;
        }

        $userGame = $game->userGames->first();

        $this->editingGameId = $game->id;
        $this->editingGameTitle = $game->title;
        $this->editingCoverImage = $game->cover_image ?? '';
        $this->editStatus = $userGame?->status?->value ?? GameStatus::Backlog->value;
        $this->editHours = $userGame ? (float) $userGame->hours_played : 0.0;
        $this->editRating = $userGame ? (float) ($userGame->rating ?? 0.0) : 0.0;
        $this->editReview = $userGame?->review ?? '';
        $this->feedbackMessage = '';
        $this->showEditModal = true;
    }

    /**
     * Fecha o modal de edição
     */
    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingGameId = null;
        $this->feedbackMessage = '';
    }

    /**
     * Salva as alterações de progresso do jogo
     */
    public function saveGameProgress(DashboardService $dashboardService): void
    {
        if (! $this->editingGameId) {
            return;
        }

        $userId = (int) Auth::id();

        $dashboardService->updateGameProgress(
            userId: $userId,
            gameId: $this->editingGameId,
            data: [
                'status' => $this->editStatus,
                'hours_played' => $this->editHours,
                'rating' => $this->editRating > 0 ? $this->editRating : null,
                'review' => $this->editReview,
            ]
        );

        $this->feedbackMessage = 'Progresso atualizado com sucesso!';
        $this->dispatch('game-progress-updated');

        $this->closeEditModal();
    }

    /**
     * Atualização rápida de status diretamente pelo card
     */
    public function quickSetStatus(int $gameId, string $status, DashboardService $dashboardService): void
    {
        $userId = (int) Auth::id();
        $game = Game::with(['userGames' => fn ($q) => $q->where('user_id', $userId)])->find($gameId);

        if (! $game) {
            return;
        }

        $userGame = $game->userGames->first();

        $dashboardService->updateGameProgress(
            userId: $userId,
            gameId: $gameId,
            data: [
                'status' => $status,
                'hours_played' => $userGame ? (float) $userGame->hours_played : 0.0,
                'rating' => $userGame?->rating,
                'review' => $userGame?->review,
            ]
        );

        $this->dispatch('game-progress-updated');
    }

    public function render(DashboardService $dashboardService): View
    {
        $userId = (int) Auth::id();

        $stats = $dashboardService->getStatistics($userId);

        $filters = [
            'search' => $this->search,
            'platform' => $this->platform,
            'library' => $this->library,
            'status' => $this->status,
            'genre' => $this->genre,
            'year' => $this->year,
            'sort_by' => $this->sortBy,
            'scope' => $this->scope,
        ];

        $games = $dashboardService->getGames($userId, $filters, 18);
        $availablePlatforms = $dashboardService->getAvailablePlatforms();
        $availableLibraries = $dashboardService->getAvailableLibraries();
        $availableGenres = $dashboardService->getAvailableGenres();

        return view('livewire.dashboard', [
            'stats' => $stats,
            'games' => $games,
            'availablePlatforms' => $availablePlatforms,
            'availableLibraries' => $availableLibraries,
            'availableGenres' => $availableGenres,
            'statuses' => GameStatus::cases(),
        ])->layout('layouts.app', [
            'title' => 'Dashboard — Minha Biblioteca',
        ]);
    }
}
