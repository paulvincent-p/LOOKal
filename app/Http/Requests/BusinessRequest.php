<?php

namespace App\Http\Requests;

use App\Models\Business;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class BusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'barangay_id' => ['required', 'exists:barangays,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'phone' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'messenger_link' => ['nullable', 'url', 'max:150'],
            'hours' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:150'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Enter a valid phone number, for example 0917 123 4567.',
        ];
    }

    /**
     * Validated data without the upload, plus the stored photo path when a new photo was sent.
     */
    public function data(?Business $existing = null): array
    {
        $data = $this->safe()->except('photo');

        if ($this->hasFile('photo')) {
            if ($existing && $existing->photo_path) {
                Storage::disk('public')->delete($existing->photo_path);
            }
            $data['photo_path'] = $this->file('photo')->store('businesses', 'public');
        }

        return $data;
    }
}
