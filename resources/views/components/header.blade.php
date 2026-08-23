@props([
    'variant' => 'landing',
    'sticky' => true,
    'logoVariant' => null,
    'showThemeSwitcher' => true,
])

<x-layouts.header :variant="$variant" :sticky="$sticky" :logoVariant="$logoVariant" :showThemeSwitcher="$showThemeSwitcher" {{ $attributes }}>
    @if (isset($nav))
        <x-slot:nav>{{ $nav }}</x-slot:nav>
    @endif
    @if (isset($actions))
        <x-slot:actions>{{ $actions }}</x-slot:actions>
    @endif
    {{ $slot ?? '' }}
</x-layouts.header>
