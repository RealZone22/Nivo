<?php

namespace App\Services;

use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SettingsService
{
    public function getSetting(string $key, $default = null, bool $isEncrypted = false, string $scope = 'system', ?int $userId = null): mixed
    {
        try {
            $query = Setting::where('key', $key)->where('scope', $scope);

            if ($scope === 'user') {
                $userId = $userId ?? Auth::id();
                $query->where('user_id', $userId);
            } else {
                $query->whereNull('user_id');
            }

            $setting = $query->first();

            if ($setting === null) {
                $setting = $this->setSetting($key, $default, isEncrypted: $isEncrypted, scope: $scope, userId: $userId);
            }

            if (Str::contains($key, ['key', 'password', 'secret', 'token'])) {
                $isEncrypted = true;
            }

            if ($isEncrypted || ($setting->encrypted ?? false)) {
                try {
                    return decrypt($setting->value);
                } catch (Exception) {
                    return $setting->value;
                }
            }

            if ($default !== null && $setting->value === null) {
                return $default;
            }

            if ($setting->value === null && $default === null) {
                return config($key);
            }

            return match ($setting->value) {
                'true' => true,
                'false' => false,
                default => $setting->value,
            };
        } catch (Exception) {
            if ($default !== null) {
                return $default;
            }

            return config($key);
        }
    }

    public function setSetting(string $key, ?string $value = null, bool $isEncrypted = false, bool $updateIfExists = false, ?bool $public = null, string $scope = 'system', ?int $userId = null): Setting
    {
        if ($scope === 'user') {
            $userId = $userId ?? Auth::id();
        } else {
            $userId = null;
        }

        $query = Setting::where('key', $key)->where('scope', $scope);

        if ($userId !== null) {
            $query->where('user_id', $userId);
        } else {
            $query->whereNull('user_id');
        }

        $setting = $query->first();

        if (Str::contains($key, ['key', 'password', 'secret', 'token'])) {
            $isEncrypted = true;
        }

        if ($setting === null) {
            $setting = new Setting;
            $setting->key = $key;
            $setting->scope = $scope;
            $setting->user_id = $userId;

            if ($value !== null) {
                $value = ($isEncrypted) ? encrypt($value) : $value;
            } elseif (config($key) !== null) {
                $value = ($isEncrypted) ? encrypt(config($key)) : config($key);
            }

            $setting->value = $value;
            $setting->encrypted = $isEncrypted;
            $setting->public = $public ?? false;
            $setting->save();
        } elseif ($updateIfExists) {
            $setting->value = ($isEncrypted) ? encrypt($value) : $value;
            $setting->encrypted = $isEncrypted;
            $setting->public = $public ?? $setting->public;
            $setting->scope = $scope;
            $setting->user_id = $userId;
            $setting->save();
        }

        return $setting;
    }

    public function updateSettings(array $settings, string $scope = 'system', ?int $userId = null): void
    {
        foreach ($settings as $key => $value) {
            $this->updateSetting($key, $value, false, null, $scope, $userId);
        }
    }

    public function updateSetting(string $key, ?string $value, bool $isEncrypted = false, ?bool $public = null, string $scope = 'system', ?int $userId = null): Setting
    {
        if ($scope === 'user') {
            $userId = $userId ?? Auth::id();
        } else {
            $userId = null;
        }

        $query = Setting::where('key', $key)->where('scope', $scope);

        if ($userId !== null) {
            $query->where('user_id', $userId);
        } else {
            $query->whereNull('user_id');
        }

        $setting = $query->first();

        if (Str::contains($key, ['key', 'password', 'secret', 'token'])) {
            $isEncrypted = true;
        }

        if ($setting !== null) {
            $setting->value = ($isEncrypted) ? encrypt($value) : $value;
            $setting->encrypted = $isEncrypted;
            $setting->public = $public ?? $setting->public;
            $setting->save();
        } else {
            $setting = $this->setSetting($key, $value, $isEncrypted, true, $public, $scope, $userId);
        }

        return $setting;
    }

    public function deleteSetting(string $key, string $scope = 'system', ?int $userId = null): bool
    {
        if ($scope === 'user') {
            $userId = $userId ?? Auth::id();
        } else {
            $userId = null;
        }

        $query = Setting::where('key', $key)->where('scope', $scope);

        if ($userId !== null) {
            $query->where('user_id', $userId);
        } else {
            $query->whereNull('user_id');
        }

        $setting = $query->first();

        if ($setting !== null) {
            return $setting->delete();
        }

        return false;
    }
}
