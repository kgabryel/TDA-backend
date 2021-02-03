<?php

namespace App\Factories;

use App\Models\Note;
use App\Models\User;

abstract class NoteFactory
{
    public static function create(array $data, User $user): Note
    {
        $note = new Note(
            [
                'title' => $data['title'],
                'content' => $data['content'],
                'background_color' => $data['backgroundColor'],
                'text_color' => $data['textColor'],
                'assigned_to_dashboard' => $data['assignedToDashboard']
            ]
        );
        $note->user()
            ->associate($user);
        $note->save();
        return $note;
    }
}
