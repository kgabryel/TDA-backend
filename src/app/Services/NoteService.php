<?php

namespace App\Services;

use App\Factories\NoteFactory;
use App\Http\Requests\Notes\NoteRequest;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;

class NoteService
{
    private User $user;

    public function assignUser(User $user): void
    {
        $this->user = $user;
    }

    public function getAll()
    {
        return Note::where('user_id', '=', $this->user->id)
            ->get();
    }

    public function find(int $id): Note
    {
        return Note::where('id', '=', $id)
            ->where('user_id', '=', $this->user->id)
            ->firstOrFail();
    }

    public function create(NoteRequest $request): Note
    {
        return NoteFactory::create($request->all(), $this->user);
    }

    public function delete(int $id): void
    {
        Note::where('id', '=', $id)
            ->where('user_id', '=', $this->user->id)
            ->firstOrFail()
            ->delete();
    }

    public function update(int $id, Request $request): Note
    {
        $note = Note::where('id', '=', $id)
            ->where('user_id', '=', $this->user->id)
            ->firstOrFail();
        $note->title = $request->get('title');
        $note->content = $request->get('content');
        $note->background_color = $request->get('backgroundColor');
        $note->text_color = $request->get('textColor');
        $note->assigned_to_dashboard = $request->get('assignedToDashboard');
        $note->update();
        return $note;
    }
}
