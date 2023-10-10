<?php

namespace AnisAronno\LaravelSettings\Helpers;

use AnisAronno\LaravelSettings\Helpers\CacheHelper;
use AnisAronno\LaravelSettings\Models\SettingsProperty;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SettingsHelper
{
    /**
     * Get Settings
     *
     * @param  string  $settingsKey
     * @return string
     * @throws \Exception
     */
    public static function getSettings(string $settingsKey): string
    {
        $key = CacheHelper::getLaravelSettingsCacheKey();
        $cacheKey =  $key.md5(serialize(['getSettings']));

        try {
            $settings = CacheHelper::init($key)->remember($cacheKey, now()->addDay(), function () use ($settingsKey) {
                return SettingsProperty::where('settings_key', $settingsKey)->first();

            });
            logger()->debug($settings['settings_value']);
            return $settings['settings_value'];

        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage(), 400);
        }

    }

    /**
     * Set Settings
     *
     * @param  string  $key
     * @param [type] $value
     * @return SettingsProperty
     * @throws \Exception
     */
    public static function setSettings(string $key, string $value): SettingsProperty
    {
        $settingProperty = new SettingsProperty();

        if ($settingProperty->where('settings_key', $key)->exists()) {
            throw new \Exception("Settings key '$key' already exists in the database", 400);
        }

        DB::beginTransaction();

        try {
            $data = ['settings_key' => $key, 'settings_value' => $value, 'user_id' => auth()->id()];
            $settingProperty->settings_key = $key;
            $settingProperty->settings_value = $value;
            $settingProperty->user_id = auth()->id() ?? null;
            $settingProperty->save();

            DB::commit();

            return $settingProperty;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage(), 400);
        }
    }


    /**
     * Get All Settings
     * @return Collection
     * @throws \Exception
     */
    public static function getAllSettings():  Collection
    {
        $key = CacheHelper::getLaravelSettingsCacheKey();
        $cacheKey =  $key.md5(serialize(['getAllSettings']));

        try {
            return CacheHelper::init($key)->remember($cacheKey, 10, function () {
                return SettingsProperty::select('settings_value', 'settings_key')->orderBy('settings_key', 'asc')->get()->flatMap(function ($name) {
                    return [$name->settings_key => $name->settings_value];
                });
            });
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage(), 400);
        }
    }


    /**
     * Update Settings
     *
     * @param  string  $key
     * @param  string|int|float  $value
     * @return SettingsProperty
     * @throws \Exception
     */
    public static function updateSettings(string $key, string $value = ''): SettingsProperty
    {
        try {
            $settings = SettingsProperty::where('settings_key', $key)->first();
            $settings->settings_value = $value;
            return $settings->save();
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage(), 400);
        }
    }
}
