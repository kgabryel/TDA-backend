<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ];
    }
}
