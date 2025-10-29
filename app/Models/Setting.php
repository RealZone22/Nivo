<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'scope',
        'user_id',
        'public',
        'encrypted',
    ];

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($setting) {
            Cache::remember('setting_'.$setting->key, 60*60*24, function () use ($setting) {
                return $setting->value;
            });
        });

        static::creating(function ($setting) {
            Cache::remember('setting_'.$setting->key, 60*60*24, function () use ($setting) {
                return $setting->value;
            });

            return true;
        });

        static::updating(function ($setting) {
            if ($setting->isDirty('value') && $setting->is_locked) {
                Log::debug('Attempted to update locked setting: '.$setting->key);

                return false;
            }

            Cache::forget('setting_'.$setting->key);

            return true;
        });

        static::deleting(function ($setting) {
            Cache::forget('setting_'.$setting->key);

            return true;
        });
    }
}
