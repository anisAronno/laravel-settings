<?php

namespace AnisAronno\LaravelSettings\Helpers;

class CacheKey
{
    /**
     * Get Settings CacheKey.
     * @return string
     */
    public static function getSettingsCacheKey(): string
    {
        return 'settings_';
    }
}
