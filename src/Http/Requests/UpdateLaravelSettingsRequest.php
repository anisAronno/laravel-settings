<?php

namespace AnisAronno\LaravelSettings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property mixed $settings_value
 * @property mixed $settings_key
 */
class UpdateLaravelSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $key = $this->route('key');

        return [
            'settings_key' => [
                'required',
                'string',
                'max:250',
                Rule::unique('settings', 'settings_key')->ignore($key, 'settings_key'),
            ],
            'settings_value' => ['required', 'string', 'max:5000'],
        ];
    }
}
