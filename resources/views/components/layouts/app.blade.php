<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      @if(userSettings('theme', 'light') === 'dark')
          class="dark bg-neutral-900"
      @else
          class="bg-white"
    @endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <link rel="icon" href="{{ asset('img/Logo.png') }}" type="image/x-icon">

    @vite('resources/css/app.css')
    @livewireStyles

    <title>{{ ($title ?? '') . ' · ' . config('app.name') }}</title>
</head>
<body>

<div x-data="{ sidebarIsOpen: false }" class="relative flex w-full flex-col md:flex-row">
    <a class="sr-only" href="#main-content">skip to the main content</a>

    <div x-cloak x-show="sidebarIsOpen" class="fixed inset-0 z-20 dark:bg-surface-dark/10 bg-surface/10 backdrop-blur-xs md:hidden"
         aria-hidden="true" x-on:click="sidebarIsOpen = false" x-transition.opacity></div>

    <nav x-cloak
         class="fixed left-0 z-30 flex h-svh w-60 shrink-0 flex-col border-r border-outline bg-surface-alt p-4 transition-transform duration-300 md:w-64 md:translate-x-0 md:relative dark:border-outline-dark dark:bg-surface-dark-alt"
         x-bind:class="sidebarIsOpen ? 'translate-x-0' : '-translate-x-60'">
        <a href="{{ route('dashboard') }}"
           class="ml-2 w-fit text-2xl text-center font-bold text-on-surface-strong dark:text-on-surface-dark-strong"
           wire:navigate>
            <img src="{{ asset('img/Logo.png') }}" alt="logo" class="inline-block size-8 mr-2 object-contain"/>
            <span>{{ config('app.name') }}</span>
        </a>

        <div class="flex flex-col gap-2 overflow-y-auto pb-6 mt-6">
            <x-sidebar-item
                icon="icon-layout-dashboard"
                :label="__('navigation.dashboard')"
                route="dashboard"/>

            <x-sidebar-item
                icon="icon-hard-drive-download"
                :label="__('navigation.downloaded_songs')"
                route="account.profile"/>

            <x-sidebar-item
                icon="icon-heart"
                :label="__('navigation.liked_songs')"
                route="account.profile"/>

            <x-sidebar-item
                icon="icon-list-video"
                :label="__('navigation.playlists')"
                route="account.profile"/>
            <ul class="pl-4">
                <li class="border-l px-2 py-0.5 border-outline dark:border-outline-dark">
                    <a href="#"
                       class="flex items-center gap-2 px-2 py-1.5 text-sm rounded-radius text-on-surface underline-offset-2 hover:bg-primary/5 hover:text-on-surface-strong focus-visible:underline focus:outline-hidden dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong">
                        <span>My Songs</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="h-svh w-full overflow-y-auto bg-surface dark:bg-surface-dark">
        <nav
            class="sticky top-0 z-10 flex items-center justify-between border-b border-outline bg-surface-alt px-4 py-2 dark:border-outline-dark dark:bg-surface-dark-alt">

            <div>
                <button type="button"
                        class="md:hidden inline-block text-on-surface dark:text-on-surface-dark cursor-pointer"
                        x-on:click="sidebarIsOpen = true">
                    <i class="icon-panel-right-close"></i>
                    <span class="sr-only">sidebar toggle</span>
                </button>
            </div>

            <x-dropdown>
                <x-dropdown.trigger>
                    <button type="button"
                            class="flex w-full cursor-pointer items-center rounded-radius gap-2 p-2 text-left text-neutral-600 hover:bg-black/5 hover:text-neutral-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white dark:focus-visible:outline-white">
                        <img src="{{ auth()->user()->avatar() }}" class="size-8 object-cover rounded-radius"
                             alt="avatar"/>
                        <div class="hidden md:flex flex-col">
                        <span
                            class="text-sm font-bold text-neutral-900 dark:text-white">{{ auth()->user()->fullName() }}</span>
                            <span class="text-xs">{{ '@' . auth()->user()->username }}</span>
                            <span class="sr-only">profile settings</span>
                        </div>
                    </button>
                </x-dropdown.trigger>

                <x-dropdown.items align="right">
                    <x-profile-item icon="icon-user" :label="__('navigation.profile')"
                                    route="account.profile"/>

                    @if(auth()->user()->admin)
                        <x-profile-item icon="icon-wrench" :label="__('navigation.admin')"
                                        route="account.profile"/>
                    @endif

                    <x-divider class="my-0"/>

                    <x-profile-item icon="icon-log-out" :label="__('navigation.logout')"
                                    route="auth.logout"
                                    external/>
                </x-dropdown.items>
            </x-dropdown>
        </nav>
        <div id="main-content" class="p-4">
            <div class="overflow-y-auto">
                {{ $slot }}
            </div>
        </div>
        @persist('player')
        <nav
            x-data="{ playing:false, duration:0, current:0, volume:1, cover:'{{ auth()->user()->avatar() }}', init() { const a=this.$refs.audio; a.addEventListener('loadedmetadata', ()=>{ this.duration = a.duration }); a.addEventListener('timeupdate', ()=>{ this.current = a.currentTime }); a.addEventListener('ended', ()=>{ this.playing = false }); a.volume = this.volume; }, toggle() { const a=this.$refs.audio; if(a.paused){ a.play(); this.playing = true } else { a.pause(); this.playing = false } }, seek(e){ const rect = e.target.getBoundingClientRect(); const pct = (e.clientX - rect.left)/rect.width; this.$refs.audio.currentTime = pct * this.duration }, setVolume(e){ this.volume = e.target.value; this.$refs.audio.volume = this.volume } }"
            x-init="init()"
            class="fixed bottom-0 left-0 right-0 z-40 text-on-surface-strong dark:text-on-surface-dark-strong border-t border-outline bg-surface-alt p-3 dark:border-outline-dark dark:bg-surface-dark-alt">
            <div class="container mx-auto flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img x-show="cover" :src="cover" :class="{ 'animate-spin': playing }"
                         class="size-8 rounded-radius object-cover" alt="cover" aria-hidden="true"/>
                    <i x-show="!cover" class="icon-disc-3 text-on-surface/60 dark:text-on-surface-dark/60"
                       :class="{ 'animate-spin': playing }" aria-hidden="true"></i>
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-on-surface-strong dark:text-on-surface-dark-strong">Track Title</span>
                        <span class="text-xs text-on-surface/80 dark:text-on-surface-dark/80">Artist Name</span>
                    </div>
                </div>

                <div class="flex flex-col flex-1 items-center gap-2">
                    <div class="flex items-center gap-4">
                        <button type="button" class="p-2">
                            <i class="icon-shuffle"></i>
                        </button>
                        <button type="button" class="p-2">
                            <i class="icon-skip-back"></i>
                        </button>
                        <button type="button" class="p-2" x-on:click="toggle()" :aria-pressed="playing.toString()">
                            <template x-if="!playing"><i class="icon-play"></i></template>
                            <template x-if="playing"><i class="icon-pause"></i></template>
                        </button>
                        <button type="button" class="p-2">
                            <i class="icon-skip-forward"></i>
                        </button>
                        <button type="button" class="p-2">
                            <i class="icon-repeat"></i>
                        </button>
                    </div>

                    <div class="w-full max-w-2xl">
                        <div class="h-1 bg-outline rounded-radius cursor-pointer" x-on:click="seek($event)">
                            <div class="h-1 bg-primary rounded-radius"
                                 :style="{ width: (duration ? (current/duration*100) : 0) + '%' }"></div>
                        </div>
                        <div class="flex justify-between text-xs mt-1">
                            <span x-text="new Date(current*1000).toISOString().substr(14,5)"></span>
                            <span x-text="new Date(duration*1000).toISOString().substr(14,5)"></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <i class="icon-volume-2"></i>
                    <input type="range" min="0" max="1" step="0.1" x-model="volume" x-on:input="setVolume($event)"
                           class="w-20"/>
                    <button type="button" class="p-2">
                        <i class="icon-list"></i>
                    </button>
                    <button type="button" class="p-2">
                        <i class="icon-mic-vocal"></i>
                    </button>
                </div>
            </div>

            <audio x-ref="audio" id="music-player" src="{{ asset('storage/someRandomFileIFoundOnMyDisk.mp3') }}"
                   preload="metadata"></audio>
        </nav>
        @endpersist
    </div>
</div>

@persist('notifications')
<x-toaster-hub/>
@endpersist

@livewireScripts
@livewire('wire-elements-modal')
@vite('resources/js/app.js')
<script src="{{ asset('js/logger.js') }}"></script>
</body>
</html>
