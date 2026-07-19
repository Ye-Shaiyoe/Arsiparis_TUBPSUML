<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'nip' => [
                'nullable',
                'string',
                'regex:/^\d{18}$/',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'cropped_photo' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nip.regex' => 'NIP harus tepat 18 digit angka.',
        ];
    }
}
