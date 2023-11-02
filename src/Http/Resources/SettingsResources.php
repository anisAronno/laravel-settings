<?php

namespace AnisAronno\LaravelSettings\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SettingsResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'settings_key' => $this->settings_key,
            'settings_value' =>  $this->settings_value,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
