<?php

namespace App\Http\Requests\Notes;

use App\Models\Note;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\HexColor;

class NoteRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:100',
            'content' => 'required|string|filled',
            'backgroundColor' => [
                'required',
                new HexColor()
            ],
            'textColor' => [
                'required',
                new HexColor()
            ],
            'assignedToDashboard' => 'required|boolean'
        ];
    }
}
