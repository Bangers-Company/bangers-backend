<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Controller handles authorization since it could be self or admin
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'email|unique:users,email,' . $this->route('user'),
            'username' => 'string|unique:users,username,' . $this->route('user'),
            'first_name' => 'string|max:100',
            'last_name' => 'string|max:100',
            'dob' => 'nullable|date',
            'bio' => 'nullable|string',
            'is_public' => 'boolean',
            'profile_media_id' => 'nullable|uuid|exists:media,id',
        ];
    }
}
