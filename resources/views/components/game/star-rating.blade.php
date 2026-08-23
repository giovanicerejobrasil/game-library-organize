@props([
    'rating' => 0,
    'max' => 5,
    'showNumber' => true,
    'size' => 'md',
])

@php
    $numRating = (float) $rating;
    $starSize = match($size) {
        'sm' => 'w-3.5 h-3.5',
        'lg' => 'w-6 h-6',
        default => 'w-4 h-4',
    };
    $textSize = match($size) {
        'sm' => 'text-xs',
        'lg' => 'text-base font-bold',
        default => 'text-sm font-semibold',
    };
@endphp

<div class="inline-flex items-center gap-1">
    <div class="flex items-center text-[var(--star-color)]">
        @for ($i = 1; $i <= $max; $i++)
            @if ($numRating >= $i)
                <!-- Full Star -->
                <svg class="{{ $starSize }} fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @elseif ($numRating >= ($i - 0.5))
                <!-- Half Star -->
                <svg class="{{ $starSize }} fill-current" viewBox="0 0 20 20">
                    <defs>
                        <linearGradient id="half-star-{{ $i }}">
                            <stop offset="50%" stop-color="var(--star-color)"/>
                            <stop offset="50%" stop-color="currentColor" stop-opacity="0.25"/>
                        </linearGradient>
                    </defs>
                    <path fill="url(#half-star-{{ $i }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @else
                <!-- Empty Star -->
                <svg class="{{ $starSize }} text-[var(--border-color)] fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endif
        @endfor
    </div>

    @if ($showNumber && $numRating > 0)
        <span class="{{ $textSize }} text-[var(--text-main)] font-['Roboto']">
            {{ number_format($numRating, 1) }}
        </span>
    @endif
</div>
