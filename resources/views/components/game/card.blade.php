@props([
    'game',
    'status' => null,
    'rating' => null,
])

@php
    $coverUrl = $game->cover_image ?? 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=600&q=80';
    $displayStatus = $status ?? $game->userGames?->first()?->status;
    $displayRating = $rating ?? $game->userGames?->first()?->rating ?? 0;
@endphp

<div class="group relative flex flex-col bg-[var(--bg-card)] rounded-[var(--radius-md)] overflow-hidden border border-[var(--border-color)] card-retro-hover cursor-pointer transition-all duration-300">
    <!-- Cover Image Container (2:3 Aspect Ratio) -->
    <div class="relative w-full aspect-[2/3] overflow-hidden bg-black/40">
        <img
            src="{{ $coverUrl }}"
            alt="{{ $game->title }}"
            class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
            loading="lazy"
        />

        <!-- Gradient Overlay for Contrast -->
        <div class="absolute inset-0 bg-gradient-to-t from-[var(--bg-card)] via-transparent to-black/50 opacity-90 group-hover:opacity-75 transition-opacity"></div>

        <!-- Top Status Badge Overlay -->
        @if ($displayStatus)
            <div class="absolute top-2.5 right-2.5 z-10 drop-shadow-md">
                <x-game.status-badge :status="$displayStatus" size="sm" />
            </div>
        @endif

        <!-- Release Year / Age Rating Chip -->
        @if ($game->release_year)
            <div class="absolute top-2.5 left-2.5 z-10">
                <span class="px-2 py-0.5 text-[11px] font-semibold bg-black/70 text-white/90 rounded-[var(--radius-sm)] backdrop-blur-sm">
                    {{ $game->release_year }}
                </span>
            </div>
        @endif
    </div>

    <!-- Game Info Card Bottom -->
    <div class="p-3.5 flex flex-col justify-between flex-1 gap-2">
        <div>
            <h3 class="font-bold text-sm text-[var(--text-main)] font-['Ubuntu'] leading-snug line-clamp-1 group-hover:text-[var(--brand-primary)] transition-colors">
                {{ $game->title }}
            </h3>

            @if ($game->developer)
                <p class="text-xs text-[var(--text-muted)] font-['Roboto'] line-clamp-1 mt-0.5">
                    {{ $game->developer }}
                </p>
            @endif
        </div>

        <!-- Rating & Platform Badges Footer -->
        <div class="flex items-center justify-between gap-2 pt-2 border-t border-[var(--border-color)]/60 text-xs">
            <x-game.star-rating :rating="$displayRating" size="sm" :showNumber="true" />

            <!-- Platforms Preview -->
            @if ($game->relationLoaded('platforms') && $game->platforms->isNotEmpty())
                <div class="flex items-center gap-1 overflow-hidden">
                    @foreach ($game->platforms->take(3) as $platform)
                        <span class="px-1.5 py-0.5 text-[10px] font-medium bg-[var(--border-color)] text-[var(--text-muted)] rounded-[var(--radius-sm)]" title="{{ $platform->name }}">
                            {{ Str::limit($platform->name, 6, '') }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
