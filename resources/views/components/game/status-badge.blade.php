@props([
    'status' => 'backlog',
    'size' => 'md',
])

@php
    use App\Enums\GameStatus;

    $statusEnum = $status instanceof GameStatus ? $status : (GameStatus::tryFrom((string) $status) ?? GameStatus::Backlog);

    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-[10px]',
        'lg' => 'px-3.5 py-1 text-sm font-semibold',
        default => 'px-2.5 py-0.5 text-xs font-medium',
    };
@endphp

<span
    class="inline-flex items-center gap-1.5 rounded-full text-white font-['Open_Sans'] shadow-sm transition-all {{ $sizeClasses }}"
    style="background-color: {{ $statusEnum->cssVariable() }};"
>
    <span class="w-1.5 h-1.5 rounded-full bg-white/80"></span>
    {{ $statusEnum->label() }}
</span>
