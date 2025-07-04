<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'], // Este es el CI + Tenant ID
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate()
    {
        $credentials = [
            'username' => $this->username,
            'password' => $this->password
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            throw ValidationException::withMessages([
                'password' => __('auth.failed'),
            ]);
        }
    }
}
