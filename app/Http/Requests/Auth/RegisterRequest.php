<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()],
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'dob' => 'required|date',
        ];
    }
}
