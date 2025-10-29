<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="dark bg-neutral-900">
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

{{ $slot }}

@persist('notifications')
<x-toaster-hub/>
@endpersist

@livewireScripts
@livewire('wire-elements-modal')
@vite('resources/js/app.js')
<script src="{{ asset('js/logger.js') }}"></script>
</body>
</html>
