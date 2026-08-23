@props([
'variant' => 'landing',
'year' => null,
'text' => null,
])

<x-layouts.footer :variant="$variant" :year="$year" :text="$text" {{ $attributes }}>
    @if (isset($links))
    <x-slot:links>{{ $links }}</x-slot:links>
    @endif
    {{ $slot ?? '' }}
</x-layouts.footer>