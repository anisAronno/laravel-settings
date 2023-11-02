<?php

namespace AnisAronno\LaravelSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $settingsKey)
 * @method static select(string $string, string $string1='')
 * @method static updateOrCreate(string[] $array, array $array1)
 * @method static findOrFail($key)
 * @property mixed|string $settings_key
 * @property mixed|string $settings_value
 */
class SettingsProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'settings_key',
        'settings_value',
    ];

    /**
     * @var mixed|string
     */
    protected $table = 'settings';

    protected $primaryKey = 'settings_key';

    public $incrementing = false;

    protected $keyType = 'string';
}
