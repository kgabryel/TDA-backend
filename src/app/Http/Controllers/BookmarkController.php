<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bookmarks\BookmarkColorRequest;
use App\Http\Requests\Bookmarks\BookmarkRequest;
use App\Models\Bookmark;
use App\Services\BookmarkService;
use App\Utils\FaviconUtils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    private BookmarkService $bookmarkService;

    public function __construct(BookmarkService $bookmarkService)
    {
        $this->bookmarkService = $bookmarkService;
    }

    public function findAll()
    {
        $this->bookmarkService->assignUser(Auth::user());
        return $this->bookmarkService->getAll();
    }

    public function find($id): Bookmark
    {
        $this->bookmarkService->assignUser(Auth::user());
        return $this->bookmarkService->find($id);
    }

    public function create(BookmarkColorRequest $request): Bookmark
    {
        $this->bookmarkService->assignUser(Auth::user());
        return $this->bookmarkService->create($request);
    }

    public function delete(int $id)
    {
        $this->bookmarkService->assignUser(Auth::user());
        $this->bookmarkService->delete($id);
        return response('', 204);
    }

    public function update($id, BookmarkColorRequest $request): Bookmark
    {
        $this->bookmarkService->assignUser(Auth::user());
        return $this->bookmarkService->update($id, $request);
    }

    public function getIcon(Request $request): array
    {
        return [
            'icon' => FaviconUtils::getAddress($request->get('href'))
        ];
    }

    public function validateData(BookmarkRequest $request, Response $response)
    {
        return $response->setStatusCode(204);
    }

    public function validateDetails(BookmarkColorRequest $request, Response $response)
    {
        return $response->setStatusCode(204);
    }
}
