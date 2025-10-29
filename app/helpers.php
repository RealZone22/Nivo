<?php

use App\Facades\Settings;
use App\Services\SettingsService;
use Illuminate\Support\Carbon;

if (!function_exists('settings')) {
    function settings($key = null, $default = null, $isLocked = false, string $scope = 'system', ?int $userId = null)
    {
        if ($key === null) {
            return new SettingsService();
        }

        return Settings::getSetting($key, $default, $isLocked, $scope, $userId);
    }
}

if (!function_exists('userSettings')) {
    function userSettings($key = null, $default = null, ?int $userId = null)
    {
        if ($key === null) {
            return auth()->user() ? auth()->user()->settings() : null;
        }

        if ($userId !== null) {
            return Settings::getSetting($key, $default, false, 'user', $userId);
        }

        if (auth()->check()) {
            return Settings::getSetting($key, $default, false, 'user', auth()->id());
        }

        return $default;
    }
}

if (!function_exists('formatFileSize')) {
    function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? min(floor(log($bytes, 1024)), count($units) - 1) : 0;
        $size = round($bytes / pow(1024, $power), 2);

        return $size . ' ' . $units[$power];
    }
}

if (!function_exists('formatDateTime')) {
    function formatDateTime($date, $format = null): string
    {
        if (blank($date)) {
            return '';
        }

        if ($format) {
            return Carbon::parse($date)->format($format);
        }

        return Carbon::parse($date)->format(settings('app.date_format', 'Y-m-d') . ' ' . settings('app.time_format', 'H:i'));
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date, $format = null): string
    {
        if (blank($date)) {
            return '';
        }

        if ($format) {
            return Carbon::parse($date)->format($format);
        }

        return Carbon::parse($date)->format(settings('app.date_format', 'Y-m-d'));
    }
}

if (!function_exists('formatTime')) {
    function formatTime(string $time, $format = null): string
    {
        if (blank($time)) {
            return '';
        }

        if ($format) {
            return Carbon::parse($time)->format($format);
        }

        return Carbon::parse($time)->format(settings('app.time_format', 'H:i'));
    }
}

if (!function_exists('carbon')) {
    function carbon($time = null, $tz = null): Carbon
    {
        return new Carbon($time, $tz);
    }
}
