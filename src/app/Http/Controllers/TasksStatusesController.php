<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use App\Services\TasksStatusesService;

class TasksStatusesController extends Controller
{
    public function findAll(TasksStatusesService $statusesService)
    {
        return [
            'statuses' => $statusesService->getAll(),
            'done' => TaskStatus::TASK_DONE_ID,
            'undone' => TaskStatus::TASK_UNDONE_ID
        ];
    }
}
