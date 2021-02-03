<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:255|exists:users',
            'password' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            '*.*' => 'invalidData',
        ];
    }
}
