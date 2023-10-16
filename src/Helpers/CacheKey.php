<?php

namespace AnisAronno\LaravelSettings\Helpers;

class CacheKey
{
    /**
     * Cache Key
     * @return string
     */
    public static function getLaravelSettingsCacheKey(): string
    {
        return "_LaravelSettings_";
    }
}
