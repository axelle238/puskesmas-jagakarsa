@props(['active' => false, 'icon' => ''])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-4 py-3 bg-gray-800 text-white border-l-4 border-aksen transition-colors'
            : 'flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors border-l-4 border-transparent';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
    <span class="text-xl w-6 text-center">{{ $icon }}</span>
    <span class="font-medium" x-show="sidebarOpen">{{ $slot }}</span>
</a>