<?php

namespace App\Console\Commands;

use App\Services\SettingsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;

class SettingsDiscoverCommand extends Command
{
    protected $signature = 'settings:discover';

    protected $description = 'Discover and create settings from their usage in the code.';

    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        parent::__construct();
        $this->settingsService = $settingsService;
    }

    public function handle()
    {
        $this->info('Discovering settings...');

        $paths = [
            app_path(),
            resource_path('views'),
        ];

        $settingKeys = [];
        $pattern = "/settings\(\s*['\"]([^'\"]+)['\"]/";

        $files = (new Finder())->in($paths)->files()->name(['*.php', '*.blade.php']);

        foreach ($files as $file) {
            $content = File::get($file->getRealPath());
            if (preg_match_all($pattern, $content, $matches)) {
                foreach ($matches[1] as $key) {
                    $settingKeys[$key] = true;
                }
            }
        }

        $uniqueKeys = array_keys($settingKeys);

        if (empty($uniqueKeys)) {
            $this->info('No settings found.');

            return 0;
        }

        $this->info('Found '.count($uniqueKeys).' unique settings. Creating them now...');

        foreach ($uniqueKeys as $key) {
            $this->settingsService->getSetting($key);
            $this->line('Ensured setting exists: '.$key);
        }

        $this->info('Settings discovery and creation complete.');

        return 0;
    }
}
