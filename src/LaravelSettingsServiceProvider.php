<?php

namespace AnisAronno\LaravelSettings;

use AnisAronno\LaravelSettings\Models\SettingsProperty;
use AnisAronno\LaravelSettings\Observers\SettingsPropertyObserver;
use Illuminate\Support\ServiceProvider;

class LaravelSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerMigration();
        SettingsProperty::observe(SettingsPropertyObserver::class);
    }

    protected function registerMigration()
    {
        $this->publishes([

            __DIR__ . '/../database/migrations/2023_01_10_072911_create_settings_table.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_settings_table.php'),

            __DIR__ . '/../database/Seeders/LaravelSettingsSeeder.php' => database_path('seeders/LaravelSettingsSeeder.php'),

         ], 'settings-migration');
    }
}
