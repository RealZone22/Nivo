<?php

namespace App\Facades;

use App\Services\SettingsService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string|null getSetting(string $key, void $default = null, bool $isEncrypted = false)
 * @method static \App\Models\Setting setSetting(string $key, string|null $value = null, bool $isEncrypted = false, bool $updateIfExists = false, bool|null $public = null)
 * @method static void updateSettings(array $settings)
 * @method static \App\Models\Setting updateSetting(string $key, string|null $value, bool $isEncrypted = false, bool|null $public = null)
 * @method static bool deleteSetting(string $key)
 *
 * @see App\Services\SettingsService
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SettingsService::class;
    }
}
