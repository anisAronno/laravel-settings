<?php

namespace AnisAronno\LaravelSettings\Observers;

use AnisAronno\LaravelSettings\Helpers\CacheKey;
use AnisAronno\LaravelSettings\Models\SettingsProperty;
use Illuminate\Support\Facades\Cache;

class SettingsPropertyObserver
{
    protected $settingsCacheKey = '';

    public function __construct()
    {
        $this->settingsCacheKey = CacheKey::getSettingsCacheKey();
    }

    /**
     * Handle the SettingsProperty "created" event.
     *
     * @param  SettingsProperty  $settings
     * @return void
     */
    public function created(SettingsProperty $settings)
    {
        $this->clearCache();
    }

    /**
     * Handle the SettingsProperty "updated" event.
     *
     * @param  SettingsProperty  $settings
     * @return void
     */
    public function updated(SettingsProperty $settings)
    {
        $this->clearCache();
    }

    /**
     * Handle the SettingsProperty "deleted" event.
     *
     * @param  SettingsProperty  $settings
     * @return void
     */
    public function deleted(SettingsProperty $settings)
    {
        $this->clearCache();
    }

    /**
     * Handle the SettingsProperty "restored" event.
     *
     * @param  SettingsProperty  $settings
     * @return void
     */
    public function restored(SettingsProperty $settings)
    {
        $this->clearCache();
    }

    /**
     * Handle the SettingsProperty "force deleted" event.
     *
     * @param  SettingsProperty  $settings
     * @return void
     */
    public function forceDeleted(SettingsProperty $settings)
    {
        $this->clearCache();
    }

    private function clearCache()
    {
        $keys = (array) Cache::get($this->settingsCacheKey, []);

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        Cache::forget($this->settingsCacheKey);
    }
}
