<?php

namespace App\Facades;

use App\Services\UnsplashService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array returnBackground()
 * @method static string|null getUTM()
 * @method static array|null getRandomUnsplashImage(void $cache = true)
 *
 * @see App\Services\UnsplashService
 */
class Unsplash extends Facade
{
    protected static function getFacadeAccessor()
    {
        return UnsplashService::class;
    }
}
