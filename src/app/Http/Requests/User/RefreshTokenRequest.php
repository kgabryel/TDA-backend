<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RefreshTokenRequest extends FormRequest
{
    public function rules()
    {
        return [
            'refresh_token' => 'required|string'
        ];
    }
}
