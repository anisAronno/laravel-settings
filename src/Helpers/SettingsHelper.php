<?php

namespace AnisAronno\LaravelSettings\Helpers;

use AnisAronno\LaravelSettings\Models\SettingsProperty;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SettingsHelper
{
    /**
     * Get Settings
     *
     * @param  string  $settingsKey
     * @return string
     * @throws Exception
     */
    public static function getSettings(string $settingsKey): string
    {
        $key = CacheHelper::getLaravelSettingsCacheKey();
 
        try {
            $settings = CacheHelper::init($key)->remember($settingsKey, now()->addDay(), function () use ($settingsKey) {
                return SettingsProperty::where('settings_key', $settingsKey)->first();

            });
            return $settings['settings_value'];

        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 400);
        }

    }

    /**
     * Set Settings
     *
     * @param  string  $key
     * @param  string  $value
     * @return SettingsProperty
     * @throws Exception
     */
    public static function setSettings(string $key, string $value): SettingsProperty
    {
        $settingProperty = new SettingsProperty();

        if ($settingProperty->where('settings_key', $key)->exists()) {
            throw new Exception("Settings key '$key' already exists in the database", 400);
        }

        DB::beginTransaction();

        try {
            $settingProperty->settings_key = $key;
            $settingProperty->settings_value = $value;
            $settingProperty->user_id = auth()->id() ?? null;
            $settingProperty->save();

            DB::commit();

            return $settingProperty;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage(), 400);
        }
    }

    /**
     * Upsert Settings
     *
     * @param  string  $key
     * @param  string  $value
     * @return SettingsProperty
     * @throws Exception
     */
    public static function upsertSettings(string $key, string $value): SettingsProperty
    {
        DB::beginTransaction();

        try {
            $settingProperty = SettingsProperty::updateOrCreate(
                ['settings_key' => $key],
                ['settings_value' => $value, 'user_id' => auth()->id() ?? null ]
            );

            DB::commit();

            return $settingProperty;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage(), 400);
        }
    }

    /**
     * Get All Settings
     * @return Collection
     * @throws Exception
     */
    public static function getAllSettings(): Collection
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
            throw new Exception($th->getMessage(), 400);
        }
    }

    /**
     * Update Settings
     *
     * @param  string  $key
     * @param  string|int|float  $value
     * @return boolean
     * @throws Exception
     */
    public static function updateSettings(string $key, string $value = ''): bool
    {
        DB::beginTransaction();

        try {
            $settings = SettingsProperty::where('settings_key', $key)->first();
            $settings->settings_value = $value;
            $settingsProperty =  $settings->save();

            DB::commit();

            return $settingsProperty;

        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage(), 400);
        }
    }

    /**
     * Undocumented function
     *
     * @param  string  $key
     * @return boolean
     * @throws Exception
     */
    public static function deleteSettings(string $key): bool
    {
        try {
            $settings = SettingsProperty::where('settings_key', $key)->first();

            if ($settings) {
                return $settings->delete();
            }

            return false;

        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), 400);
        }
    }
}
