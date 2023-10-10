<?php

namespace AnisAronno\LaravelSettings;

use AnisAronno\LaravelSettings\Models\SettingsProperty;
use AnisAronno\LaravelSettings\Observers\LaravelSettingsObserver;
use AnisAronno\LaravelSettings\RouteServiceProvider;
use Illuminate\Support\ServiceProvider;

class LaravelSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerMigration();
        $this->registerConfig();
        SettingsProperty::observe(LaravelSettingsObserver::class);
    }


    protected function registerMigration()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->publishes([
            __DIR__ . '/Database/Migrations/2023_01_10_072911_create_settings_table.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_settings_table.php'),
         ], 'settings-migration');
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
           __DIR__.'/Config/laravel-settings.php' => config_path('laravel-settings.php'),
        ], 'laravel-settings');

        $this->mergeConfigFrom(
            __DIR__.'/Config/laravel-settings.php',
            'laravel-settings'
        );
    }
}
