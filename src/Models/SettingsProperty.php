<?php

namespace AnisAronno\LaravelSettings\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, string $settingsKey)
 * @method static select(string $string, string $string1)
 * @method static updateOrCreate(string[] $array, array $array1)
 * @method static findOrFail($key)
 * @property mixed|string $settings_key
 * @property mixed|string $settings_value
 * @property mixed|string $user_id
 */
class SettingsProperty extends Model
{
    use HasFactory;

    /**
     * @var mixed|string
     */
     protected $table = 'settings';

    protected $fillable = [
        'settings_key',
        'settings_value',
        'user_id',
    ];

    protected $primaryKey = 'settings_key';

    public $incrementing = false;

    protected $keyType = 'string';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
