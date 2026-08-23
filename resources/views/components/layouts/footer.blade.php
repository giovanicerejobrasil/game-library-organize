@props([
'variant' => 'landing',
'year' => null,
'text' => null,
])

@php
$currentYear = $year ?? date('Y');
$defaultText = $variant === 'landing'
? "Game Library Organize. Desenvolvido com Laravel 13, Livewire 4, Elasticsearch e PostgreSQL."
: "Game Library Organize. Todos os direitos reservados.";
$displayText = $text ?? $defaultText;
@endphp

<footer {{ $attributes->merge(['class' => 'border-t border-[var(--border-color)] bg-[var(--bg-card)] py-6 sm:py-8 text-center text-xs text-[var(--text-muted)] mt-auto transition-colors duration-300']) }}>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-center gap-3">
        <p class="font-['Roboto']">&copy; {{ $currentYear }} {{ $displayText }}</p>

        @if (isset($links))
        <div class="flex items-center gap-4 text-xs font-['Open_Sans']">
            {{ $links }}
        </div>
        @else
        {{ $slot ?? '' }}
        @endif
    </div>
</footer>