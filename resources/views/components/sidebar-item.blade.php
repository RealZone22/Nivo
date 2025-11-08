@props([
    'icon',
    'label',
    'url' => null,
    'route' => null,
    'external' => false,
])

@if(request()->routeIs($route . '*'))
    <a href="{{ $url ?? route($route) }}"
       @if(!$external) wire:navigate @endif
       class="flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong group relative bg-black/10 dark:bg-white/10 overflow-hidden h-9"
       :class="(!sidebarPinned && !sidebarHovered) ? 'md:w-10' : 'w-full px-2'">
        <div class="flex items-center w-full"
             :class="(!sidebarPinned && !sidebarHovered) ? 'md:w-10 md:justify-center' : 'w-full gap-2'">
            <i class="{{ $icon }} mr-1"></i>
            <span class="transition-all duration-300 whitespace-nowrap"
                  :class="(!sidebarPinned && !sidebarHovered) ? 'block md:opacity-0 md:w-0' : 'opacity-100 w-auto'">
            {{ $label }}
        </span>
        </div>
    </a>
@else
    <a href="{{ $url ?? route($route) }}"
       @if(!$external) wire:navigate @endif
       class="flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong group relative overflow-hidden h-9"
       :class="(!sidebarPinned && !sidebarHovered) ? 'md:w-10' : 'w-full px-2'">
        <div class="flex items-center w-full"
             :class="(!sidebarPinned && !sidebarHovered) ? 'md:w-10 md:justify-center' : 'w-full gap-2'">
            <i class="{{ $icon }} mr-1"></i>
            <span class="transition-all duration-300 whitespace-nowrap"
                  :class="(!sidebarPinned && !sidebarHovered) ? 'block md:opacity-0 md:w-0' : 'opacity-100 w-auto'">
            {{ $label }}
        </span>
        </div>
    </a>
@endif
