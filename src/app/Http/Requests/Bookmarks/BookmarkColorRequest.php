<?php

namespace App\Http\Requests\Bookmarks;

use App\Rules\HexColor;
use Illuminate\Foundation\Http\FormRequest;

class BookmarkColorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
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
