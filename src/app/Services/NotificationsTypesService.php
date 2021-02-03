<?php

namespace App\Services;

namespace App\Services;

use App\Models\NotificationsType;

class NotificationsTypesService
{
    public function getAll()
    {
        return NotificationsType::all();
    }
}
