<?php

namespace AnisAronno\LaravelSettings\Helpers;

use Illuminate\Cache\TaggedCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

/**
 * @property $files
 * @method path($key)
 */
class CacheHelper
{
    /**
     * Initialization cache instance
     *
     * @param  string|null  $key
     * @return Cache|TaggedCache
     */
    public static function init(string $key = null)
    {
        if (Cache::supportsTags() && !is_null($key)) {
            return Cache::tags($key);
        } else {
            return cache();
        }
    }

    /**
     * Forget Cache Key
     *
     * @param  string  $key
     * @return boolean
     */
    public static function forgetCache(string $key): bool
    {
        if (Cache::supportsTags()) {
            return  Cache::tags($key)->flush();
        } else {
            return self::forgetCacheByKey($key);
        }

    }

    /**
     * Forget Cache key by tag
     *
     * @param  string  $key
     * @return boolean
     */
    private static function forgetCacheByKey(string $key): bool
    {
        try {
            $allKeys = self::getCacheKeys($key);

            foreach ($allKeys as  $oldKey) {
                if (Cache::has($oldKey)) {
                    Cache::forget($oldKey);
                } else {
                    Cache::flush();
                    break;
                }
            }
            return true;
        } catch (\Throwable $th) {
            Cache::flush();
            return true;
        }
    }

    /**
     * Get ALl Cache Key
     * @param  string|null  $key
     * @return array
      */
    private static function getCacheKeys(string $key = null): array
    {
        switch (Cache::getDefaultDriver()) {
            case 'file':
                return self::getFileCacheKeys();
            case 'redis':
                return self::getRedisCacheKeys($key);
            case 'memcached':
                return self::getMemcachedCacheKeys($key);
            default:
                return [];
        }
    }

    /**
     * Get File Cache path
     *
     * @return array
     */
    private static function getFileCacheKeys(): array
    {
        try {
            $storage = Cache::getStore();
            $filesystem = $storage->getFilesystem();

            $cachePath = $storage->getDirectory();

            $keys = [];
            $files = $filesystem->allFiles($cachePath);

            foreach ($files as $file) {
                $cacheKey = str_replace([$cachePath, DIRECTORY_SEPARATOR, '.php'], '', $file);
                $keys[] = $cacheKey;
            }

            return $keys;
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function forget($key)
    {
        if ($this->files->exists($file = $this->path($key))) {
            return $this->files->delete($file);
        }

        return false;
    }

    /**
     * Get Redis cache key
     *
     * @param  string|null  $key
     * @return array
     */
    private static function getRedisCacheKeys(string $key = null): array
    {
        try {
            $cacheKeys = Redis::connection('cache')->keys('*');

            $modifiedKeys = [];

            foreach ($cacheKeys as $element) {
                if ($key === null) {
                    if (preg_match('/^.*:(.*)$/', $element, $matches)) {
                        $modifiedKeys[] = $matches[1];
                    }
                } else {
                    if (preg_match('/^.*:' . preg_quote($key) . '(.*)$/', $element, $matches)) {
                        $modifiedKeys[] = $key . $matches[1];
                    }
                }
            }

            return $modifiedKeys;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * get memcache key
     *
     * @param  string|null  $key
     * @return array
     */
    private static function getMemcachedCacheKeys(string $key = null): array
    {
        try {
            $cacheKeys = [];
            $memcached = app('cache')->getStore()->getMemcached();
            $allKeys = $memcached->getAllKeys();

            foreach ($allKeys as $element) {
                if ($key === null) {
                    if (preg_match('/^.*:(.*)$/', $element, $matches)) {
                        $cacheKeys[] = $matches[1];
                    }
                } else {
                    if (preg_match('/^.*:' . preg_quote($key) . '(.*)$/', $element, $matches)) {
                        $cacheKeys[] = $key . $matches[1];
                    }
                }
            }

            return $cacheKeys;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Define Cache Key
     *
     * @return string
     */
    public static function getLaravelSettingsCacheKey(): string
    {
        return '_Laravel_Settings_';
    }

}
