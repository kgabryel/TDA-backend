<?php

namespace App\Services;

use App\Factories\BookmarkFactory;
use App\Models\Bookmark;
use App\Models\User;
use App\Utils\FaviconUtils;
use Illuminate\Http\Request;

class BookmarkService
{
    private User $user;

    public function assignUser(User $user): void
    {
        $this->user = $user;
    }

    public function getAll()
    {
        return Bookmark::where('user_id', '=', $this->user->id)
            ->get();
    }

    public function paginate(int $perPage = 15)
    {
        return Bookmark::where('user_id', '=', $this->user->id)
            ->paginate($perPage);
    }

    public function find(int $id): Bookmark
    {
        return Bookmark::where('id', '=', $id)
            ->where('user_id', '=', $this->user->id)
            ->firstOrFail();
    }

    public function create(Request $request): Bookmark
    {
        return BookmarkFactory::create($request->all(), $this->user);
    }

    public function delete(int $id): void
    {
        Bookmark::where('id', '=', $id)
            ->where('user_id', '=', $this->user->id)
            ->firstOrFail()
            ->delete();
    }

    public function update(int $id, Request $request): Bookmark
    {
        $bookmark = Bookmark::where('id', '=', $id)
            ->where('user_id', '=', $this->user->id)
            ->firstOrFail();
        $bookmark->title = $request->get('title');
        $bookmark->href = $request->get('href');
        $bookmark->icon = FaviconUtils::getAddress($request->get('href'));
        $bookmark->background_color = $request->get('backgroundColor');
        $bookmark->text_color = $request->get('textColor');
        $bookmark->update();
        return $bookmark;
    }
}
