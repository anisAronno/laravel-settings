<?php

namespace AnisAronno\LaravelSettings\Http\Requests;

use AnisAronno\LaravelSettings\Rules\SettingsKeyUniqueRule;
use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'settings_key' => ['required', 'string', 'max:250', new SettingsKeyUniqueRule()],
            'settings_value' => ['required', 'string', 'max:5000'],
        ];
    }
}
