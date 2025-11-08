@props([
    'icon',
    'label',
    'url' => null,
    'route' => null,
    'external' => false,
])

<a
    href="{{ $url ?? route($route) }}"
    @if(!$external) wire:navigate @endif
    {{ $attributes->twMerge('flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-hidden dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white') }}
    role="menuitem">
    <i class="{{ $icon }} text-lg"></i>
    <span>{{ $label }}</span>
</a>
