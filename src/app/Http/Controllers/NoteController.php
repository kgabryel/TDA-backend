<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notes\NoteRequest;
use App\Models\Note;
use App\Services\NoteService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    private NoteService $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    public function findAll()
    {
        $this->noteService->assignUser(Auth::user());
        return $this->noteService->getAll();
    }

    public function find(int $id): Note
    {
        $this->noteService->assignUser(Auth::user());
        return $this->noteService->find($id);
    }

    public function create(NoteRequest $request): Note
    {
        $this->noteService->assignUser(Auth::user());
        return $this->noteService->create($request);
    }

    public function delete(int $id)
    {
        $this->noteService->assignUser(Auth::user());
        $this->noteService->delete($id);
        return response(null, 204);
    }

    public function update(int $id, NoteRequest $request): Note
    {
        $this->noteService->assignUser(Auth::user());
        return $this->noteService->update($id, $request);
    }

    public function validateData(NoteRequest $request, Response $response)
    {
        return $response->setStatusCode(204);
    }
}
