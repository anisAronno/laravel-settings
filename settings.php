<?php

if (!function_exists('getAllSettings')) {
    /**
     * @throws Exception
     */
    function getAllSettings(): \Illuminate\Support\Collection
    {
        return AnisAronno\LaravelSettings\Helpers\SettingsHelper::getAllSettings();
    }
}

if (!function_exists('getSettings')) {
    /**
     * @throws Exception
     */
    function getSettings(string $key): string
    {
        return AnisAronno\LaravelSettings\Helpers\SettingsHelper::getSettings($key);
    }
}

if (!function_exists('setSettings')) {
    /**
     * @throws Exception
     */
    function setSettings(string $key, string  $value): \AnisAronno\LaravelSettings\Models\SettingsProperty
    {
        return AnisAronno\LaravelSettings\Helpers\SettingsHelper::setSettings($key, $value);
    }
}

if (!function_exists('updateSettings')) {
    /**
     * @throws Exception
     */
    function updateSettings(string $key, string  $value): \AnisAronno\LaravelSettings\Models\SettingsProperty
    {
        return AnisAronno\LaravelSettings\Helpers\SettingsHelper::updateSettings($key, $value);
    }
}
