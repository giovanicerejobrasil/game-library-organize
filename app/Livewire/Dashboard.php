<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\GameStatus;
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

    public ?int $selectedGameId = null;

    /**
     * Redireciona para a página de detalhes do jogo
     */
    public function showGameModal(int $gameId)
    {
        return $this->redirect(route('games.show', $gameId), navigate: true);
    }

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
            'title' => 'Minha Biblioteca',
        ]);
    }
}
