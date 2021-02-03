<?php

namespace App\Http\Requests\Bookmarks;

use Illuminate\Foundation\Http\FormRequest;

class IconRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'href' => 'required|string|filled|url'
        ];
    }
}
