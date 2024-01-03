<?php

namespace AnisAronno\LaravelSettings\Helpers;

use AnisAronno\LaravelSettings\Models\SettingsProperty;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use AnisAronno\LaravelSettings\Helpers\CacheKey;

class SettingsHelper
{
    /**
     * Check if a Settings Key Exists
     *
     * @param  string  $settingsKey
     * @return bool
     */
    public static function hasSettings(string $settingsKey): bool
    {
        $cacheKey = CacheKey::getSettingsCacheKey();
        $key = $cacheKey.md5($settingsKey.'_'.'isExist');

        try {
            $settings = Cache::remember(
                $key,
                now()->addDay(),
                function () use ($settingsKey) {
                    return SettingsProperty::where('settings_key', $settingsKey)->exists();
                }
            );

            Cache::put($cacheKey, array_merge(Cache::get($cacheKey, []), [$key]));

            return $settings;
        } catch (\Throwable $th) {
            return false;
        }
    }


    /**
     * Get Settings
     *
     * @param  string  $settingsKey
     * @return string
     * @throws Exception
     */
    public static function getSettings(string $settingsKey): string
    {
        $cacheKey = CacheKey::getSettingsCacheKey();
        $key = $cacheKey.md5($settingsKey);
        try {
            $settings = Cache::remember(
                $key,
                now()->addDay(),
                function () use ($settingsKey) {
                    return   SettingsProperty::select('settings_value')->find($settingsKey);
                }
            );

            Cache::put($cacheKey, array_merge(Cache::get($cacheKey, []), [$key]));

            if(isset($settings['settings_value'])) {
                return $settings['settings_value'];
            } else {
                throw new Exception('The '.$settingsKey.' key was not found in the settings table.', 404);
            }

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
        if(!self::isKeyAlphaDash($key, 'ascii')) {
            throw new Exception("The settings key field must only contain letters, numbers, dashes, and underscores", 400);
        }

        $settingProperty = new SettingsProperty();

        if ($settingProperty->where('settings_key', $key)->exists()) {
            throw new Exception("Settings key '$key' already exists in the database", 400);
        }

        DB::beginTransaction();

        try {
            $settingProperty->settings_key = $key;
            $settingProperty->settings_value = $value;
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
        if(!self::isKeyAlphaDash($key, 'ascii')) {
            throw new Exception("The settings key field must only contain letters, numbers, dashes, and underscores", 400);
        }

        DB::beginTransaction();

        try {
            $settingProperty = SettingsProperty::updateOrCreate(
                ['settings_key' => $key],
                ['settings_value' => $value]
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
    public static function getAllSettings(Request $request): Collection
    {
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $settingsCacheKey = CacheKey::getSettingsCacheKey();
        $key = $settingsCacheKey.md5($queryString);

        try {
            $settings = Cache::remember(
                $key,
                now()->addDay(),
                function () use ($request) {
                    return SettingsProperty::select('settings_value', 'settings_key')->orderBy('settings_key', 'asc')->get()->flatMap(function ($name) {
                        return [$name->settings_key => $name->settings_value];
                    });
                }
            );

            Cache::put($settingsCacheKey, array_merge(Cache::get($settingsCacheKey, []), [$key]));
            return $settings;
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
        if(!self::isKeyAlphaDash($key, 'ascii')) {
            throw new Exception("The settings key field must only contain letters, numbers, dashes, and underscores", 400);
        }

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

    /**
     * Validate that an attribute contains only alpha-numeric characters, dashes, and underscores.
     * If the 'ascii' option is passed, validate that an attribute contains only ascii alpha-numeric characters,
     * dashes, and underscores.
     *
     * @param  mixed  $value
     * @param  string  $parameters
     * @return bool
     */
    private static function isKeyAlphaDash($value, string $parameters = 'ascii'): bool
    {
        if (!is_string($value) && !is_numeric($value)) {
            return false;
        }

        if (isset($parameters) && $parameters === 'ascii') {
            return preg_match('/\A[a-zA-Z0-9_-]+\z/u', $value) > 0;
        }

        return preg_match('/\A[\pL\pM\pN_-]+\z/u', $value) > 0;
    }
}
