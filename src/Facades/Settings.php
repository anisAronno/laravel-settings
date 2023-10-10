<?php

namespace AnisAronno\LaravelSettings\Facades;

use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{
    /**
      * @method static string getDefaultFavIcon()
      * @method static string getDefaultBanner()
      * @method static string getDefaultAvatar()
      *
      * @see \AnisAronno\LaravelSettings\Models\SettingsProperty
      */
    protected static function getFacadeAccessor()
    {
        return 'Settings';
    }

}
