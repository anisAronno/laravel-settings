<?php

use AnisAronno\LaravelSettings\Helpers\SettingsHelper;
use AnisAronno\LaravelSettings\Models\SettingsProperty;
use Illuminate\Support\Collection;

if (!function_exists('hasSettings')) {
    /**
     * Check if a Settings Key Exists
     *
     * @param string $key
     * @return boolean
     */
    function hasSettings(string $key): bool
    {
        return SettingsHelper::hasSettings($key);
    }
}

if (!function_exists('getSettings')) {
    /**
     * Get Settings
     *
     * @param string $key
     * @return string|null
     * @throws Exception
     */
    function getSettings(string $key): string
    {
        return SettingsHelper::getSettings($key);
    }
}

if (!function_exists('getAllSettings')) {

    /**
     * Get all Settings
     *
     * @return Collection
     * @throws Exception
     */
    function getAllSettings(): Collection
    {
        return SettingsHelper::getAllSettings();
    }
}

if (!function_exists('setSettings')) {

    /**
     * Set Settings
     *
     * @param string $key
     * @param string $value
     * @return SettingsProperty
     * @throws Exception
     */
    function setSettings(string $key, string  $value): SettingsProperty
    {
        if (!app()->runningInConsole()) {
            abort_if(!auth()->user(), 403, 'Unauthenticated.');
        }

        return SettingsHelper::setSettings($key, $value);
    }
}

if (!function_exists('updateSettings')) {

    /**
     * Update Settings
     *
     * @param string $key
     * @param string $value
     * @return bool
     * @throws Exception
     */
    function updateSettings(string $key, string  $value): bool
    {
        abort_if(!auth()->user(), 403, 'Unauthenticated.');
        return SettingsHelper::updateSettings($key, $value);
    }
}

if (!function_exists('upsertSettings')) {

    /**
     * Create or update Settings
     *
     * @param string $key
     * @param string $value
     * @return SettingsProperty
     * @throws Exception
     */
    function upsertSettings(string $key, string  $value): SettingsProperty
    {
        abort_if(!auth()->user(), 403, 'Unauthenticated.');

        return SettingsHelper::upsertSettings($key, $value);
    }
}

if (!function_exists('deleteSettings')) {

    /**
     * Delete Settings
     *
     * @param  string  $key
     * @return bool
     * @throws Exception
     */
    function deleteSettings(string $key): bool
    {
        abort_if(!auth()->user(), 403, 'Unauthenticated.');
        return SettingsHelper::deleteSettings($key);
    }
}
