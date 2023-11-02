<?php

namespace AnisAronno\LaravelSettings\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $settings_key
 * @property mixed $settings_value
 */
class StoreLaravelSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'settings_key' => ['required', 'string', 'max:250', 'alpha_dash:ascii', 'unique:settings,settings_key'],
            'settings_value' => ['required', 'string', 'max:5000'],
        ];
    }
}
