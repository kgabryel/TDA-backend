<?php

namespace App\Factories;

use App\Models\Bookmark;
use App\Models\User;
use App\Utils\FaviconUtils;

abstract class BookmarkFactory
{
    public static function create(array $data, User $user): Bookmark
    {
        $bookmark = new Bookmark(
            [
                'title' => $data['title'],
                'href' => $data['href'],
                'icon' => FaviconUtils::getAddress($data['href']),
                'background_color' => $data['backgroundColor'],
                'text_color' => $data['textColor'],
                'assigned_to_dashboard' => $data['assignedToDashboard']
            ]
        );
        $bookmark->user()
            ->associate($user);
        $bookmark->save();
        return $bookmark;
    }
}
