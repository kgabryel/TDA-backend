<?php

namespace App\Http\Requests\Bookmarks;

use App\Rules\HexColor;
use Illuminate\Foundation\Http\FormRequest;

class BookmarkAddRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|filled|string|max:50',
            'href' => 'required|filled||string|url',
            'backgroundColor' => [
                'required',
                'filled',
                'string',
                new HexColor()
            ],
            'textColor' => [
                'required',
                'filled',
                'string',
                new HexColor()
            ]
        ];
    }

}
