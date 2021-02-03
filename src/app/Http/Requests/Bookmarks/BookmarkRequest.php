<?php

namespace App\Http\Requests\Bookmarks;

use Illuminate\Foundation\Http\FormRequest;

class BookmarkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|filled|string|max:50',
            'href' => 'required|filled||string|url',
            'assignedToDashboard' => 'required|boolean'
        ];
    }
}
