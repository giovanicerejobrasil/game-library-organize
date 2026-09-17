<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\User;
use App\Models\UserGame;
use App\Services\Elasticsearch\GameSearchService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class GameDetail extends Component
{
    public Game $game;

    public ?UserGame $userGame = null;

    public bool $inUserLibrary = false;

    // Dados de Progresso Pessoal do Usuário
    public string $status = 'backlog';

    public float|int|string $hours_played = 0;

    public ?float $rating = null;

    public string $review = '';

    public bool $showDeleteModal = false;

    /**
     * Inicializa o componente com o jogo resolvido via Route Model Binding
     */
    public function mount(Game $game): void
    {
        $this->game = $game;
        $this->game->loadMissing(['platforms', 'libraries']);

        $this->loadUserProgress();
    }

    /**
     * Carrega as informações de progresso do usuário logado para este jogo
     */
    public function loadUserProgress(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->userGame = $this->game->userGames()
            ->where('user_id', $user->id)
            ->first();

        if ($this->userGame) {
            $this->inUserLibrary = true;
            $this->status = $this->userGame->status->value;
            $this->hours_played = (float) $this->userGame->hours_played;
            $this->rating = $this->userGame->rating !== null ? (float) $this->userGame->rating : null;
            $this->review = $this->userGame->review ?? '';
        } else {
            $this->inUserLibrary = false;
            $this->status = GameStatus::Backlog->value;
            $this->hours_played = 0;
            $this->rating = null;
            $this->review = '';
        }
    }

    /**
     * Alterna a nota de avaliação por estrelas (0 a 5)
     */
    public function setRating(float $value): void
    {
        $this->rating = ($this->rating === $value) ? null : $value;
    }

    /**
     * Salva ou atualiza o progresso e avaliação pessoal do usuário para este jogo
     */
    public function saveUserProgress(GameSearchService $searchService): void
    {
        $this->validate([
            'status' => ['required', Rule::enum(GameStatus::class)],
            'hours_played' => ['nullable', 'numeric', 'min:0', 'max:99999.99'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5.0'],
            'review' => ['nullable', 'string', 'max:10000'],
        ], [
            'status.required' => 'Selecione o seu status para este jogo.',
            'hours_played.numeric' => 'As horas jogadas devem ser numéricas.',
            'hours_played.min' => 'As horas jogadas não podem ser negativas.',
            'rating.min' => 'A avaliação deve ser de no mínimo 0 estrelas.',
            'rating.max' => 'A avaliação deve ser de no máximo 5 estrelas.',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $statusEnum = GameStatus::from($this->status);
        $finishedAt = $statusEnum === GameStatus::Finished ? Carbon::now() : null;

        $this->userGame = UserGame::updateOrCreate(
            [
                'user_id' => $user->id,
                'game_id' => $this->game->id,
            ],
            [
                'status' => $statusEnum,
                'hours_played' => (float) $this->hours_played,
                'rating' => $this->rating,
                'review' => trim($this->review) !== '' ? trim($this->review) : null,
                'finished_at' => $finishedAt,
            ]
        );

        $this->inUserLibrary = true;

        // Sincroniza Elasticsearch com o estado atualizado
        $searchService->indexGame($this->game->fresh(['platforms', 'libraries']));

        session()->flash('status', 'Progresso salvo na sua biblioteca com sucesso!');
    }

    /**
     * Exibe o modal de confirmação para remoção do jogo
     */
    public function confirmRemoval(): void
    {
        $this->showDeleteModal = true;
    }

    /**
     * Cancela a remoção e fecha o modal
     */
    public function cancelRemoval(): void
    {
        $this->showDeleteModal = false;
    }

    /**
     * Remove o jogo da biblioteca pessoal do usuário
     */
    public function removeFromLibrary(GameSearchService $searchService): void
    {
        /** @var User $user */
        $user = Auth::user();

        UserGame::where('user_id', $user->id)
            ->where('game_id', $this->game->id)
            ->delete();

        $this->userGame = null;
        $this->inUserLibrary = false;
        $this->showDeleteModal = false;
        $this->status = GameStatus::Backlog->value;
        $this->hours_played = 0;
        $this->rating = null;
        $this->review = '';

        // Sincroniza Elasticsearch com o estado atualizado
        $searchService->indexGame($this->game->fresh(['platforms', 'libraries']));

        session()->flash('status', 'Jogo removido da sua biblioteca pessoal.');
    }

    /**
     * Extrai a URL segura de incorporação (embed) para trailers do YouTube
     */
    public function getYoutubeEmbedUrlProperty(): ?string
    {
        if (empty($this->game->trailer_url)) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->game->trailer_url, $matches)) {
            return "https://www.youtube-nocookie.com/embed/{$matches[1]}";
        }

        return null;
    }

    public function render(): View
    {
        return view('livewire.game-detail', [
            'statuses' => GameStatus::cases(),
            'youtubeEmbedUrl' => $this->youtubeEmbedUrl,
        ])->layout('layouts.app', [
            'title' => $this->game->title,
        ]);
    }
}
