<?php

namespace AnisAronno\LaravelSettings\Observers;

use AnisAronno\LaravelSettings\Helpers\CacheHelper;
use AnisAronno\LaravelSettings\Models\SettingsProperty;

class LaravelSettingsObserver
{
    protected $settingsCacheKey = '';

    public function __construct()
    {
        $this->settingsCacheKey = CacheHelper::getLaravelSettingsCacheKey();
    }

    /**
     * Handle the SettingsProperty "created" event.
     *
     * @param  SettingsProperty  $settings
     * @return void
     */
    public function created(SettingsProperty $settings): void
    {
        CacheHelper::forgetCache($this->settingsCacheKey);
    }

    /**
     * Handle the SettingsProperty "updated" event.
     *
     * @param  SettingsProperty  $settings
     * @return void
     */
    public function updated(SettingsProperty $settings): void
    {
        CacheHelper::forgetCache($this->settingsCacheKey);
    }

    /**
     * Handle the SettingsProperty "deleted" event.
     *
     * @param  SettingsProperty  $settings
     * @return void
     */
    public function deleted(SettingsProperty $settings): void
    {
        CacheHelper::forgetCache($this->settingsCacheKey);
    }

    /**
     * Handle the SettingsProperty "restored" event.
     *
     * @param  SettingsProperty  $settings
     * @return void
     */
    public function restored(SettingsProperty $settings): void
    {
        CacheHelper::forgetCache($this->settingsCacheKey);
    }

    /**
     * Handle the SettingsProperty "force deleted" event.
     *
     * @param  SettingsProperty  $settings
     * @return void
     */
    public function forceDeleted(SettingsProperty $settings): void
    {
        CacheHelper::forgetCache($this->settingsCacheKey);
    }
}
