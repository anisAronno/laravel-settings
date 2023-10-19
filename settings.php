<?php

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
        return AnisAronno\LaravelSettings\Helpers\SettingsHelper::hasSettings($key);
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
        return AnisAronno\LaravelSettings\Helpers\SettingsHelper::getSettings($key);
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
        return AnisAronno\LaravelSettings\Helpers\SettingsHelper::getAllSettings();
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
        return AnisAronno\LaravelSettings\Helpers\SettingsHelper::setSettings($key, $value);
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
        return AnisAronno\LaravelSettings\Helpers\SettingsHelper::updateSettings($key, $value);
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
        return AnisAronno\LaravelSettings\Helpers\SettingsHelper::upsertSettings($key, $value);
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
        return AnisAronno\LaravelSettings\Helpers\SettingsHelper::deleteSettings($key);
    }
}
