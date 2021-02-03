<?php

namespace App\Http\Controllers;

use App\Services\NotificationsTypesService;

class NotificationsTypeController extends Controller
{
    public function findAll(NotificationsTypesService $notificationsTypesService)
    {
        return $notificationsTypesService->getAll();
    }
}
