<?php

namespace App\Services;

namespace App\Services;

use App\Models\TaskStatus;

class TasksStatusesService
{
    public function getAll()
    {
        return TaskStatus::all();
    }
}
