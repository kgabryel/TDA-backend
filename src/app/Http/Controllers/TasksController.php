<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tasks\PeriodicTaskRequest;
use App\Http\Requests\Tasks\SingleTaskRequest;
use App\Http\Requests\Tasks\TaskStatusRequest;
use App\Models\Task;
use App\Models\TaskGroup;
use App\Services\AlarmsService;
use App\Services\TasksService;
use App\Utils\DateUtils;
use App\Utils\TasksUtils;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    private TasksService $tasksService;

    public function __construct(TasksService $tasksService)
    {
        $this->tasksService = $tasksService;
    }

    public function findAll()
    {
        $this->tasksService->assignUser(Auth::user());
        return $this->tasksService->getAll();
    }

    public function createSingle(SingleTaskRequest $request, AlarmsService $alarmsService): Task
    {
        $mainTask = null;
        if (isset($request->get('task')['mainTask'])) {
            $mainTask = Task::find($request->get('task')['mainTask']);
        }
        $this->tasksService->assignUser(Auth::user());
        $task = $this->tasksService->createSingle($request->get('task'), $mainTask);
        if ($request->get('alarm') !== null) {
            $alarmsService->assignUser(Auth::user());
            $alarm = $alarmsService->createSingle($request->get('alarm'));
            $alarm->task()
                ->associate($task);
            $alarm->save();
        }
        return $task->fresh();
    }

    public function createPeriodic(PeriodicTaskRequest $request, AlarmsService $alarmsService
    ): TaskGroup
    {
        $taskData = $request->get('task');
        $this->tasksService->assignUser(Auth::user());
        $dates = DateUtils::getDatesTillNextMonthEnd(
            $taskData['interval'],
            $taskData['intervalType'],
            new Carbon($taskData['start']),
            $taskData['stop'] !== null ? new Carbon($taskData['stop']) : null
        );
        $taskGroup = $this->tasksService->createPeriodic($taskData, $dates);
        if ($request->get('alarm') !== null) {
            $alarmsService->assignUser(Auth::user());
            $alarmData = $request->get('alarm');
            $alarmData['start'] = $taskData['start'];
            $alarmData['stop'] = $taskData['stop'];
            $alarmData['interval'] = $taskData['interval'];
            $alarmData['intervalType'] = $taskData['intervalType'];
            TasksUtils::assignAlarm(
                $taskGroup,
                $alarmsService->createPeriodic(
                    $alarmData,
                    $dates,
                    $taskGroup
                )
            );
        }
        return $taskGroup;
    }

    public function validateSingleTask(SingleTaskRequest $request, Response $response)
    {
        return $response->setStatusCode(204);
    }

    public function validatePeriodicTask(PeriodicTaskRequest $request, Response $response)
    {
        return $response->setStatusCode(204);
    }

    public function changeStatus(
        string $id, TaskStatusRequest $request, Response $response, AlarmsService $alarmsService
    )
    {
        $this->tasksService->assignUser(Auth::user());
        $task = $this->tasksService->getSingle($id);
        if ($task === null) {
            return $response->setStatusCode(404);
        }
        $task = $this->tasksService->changeStatus($task, $request->get('status'), $alarmsService);
        $mainTask = $task->mainTask()
            ->first();
        return $mainTask ?? $task;
    }

    public function deleteSingle(string $id, Response $response)
    {
        $this->tasksService->assignUser(Auth::user());
        $task = $this->tasksService->getSingle($id);
        if ($task === null) {
            return $response->setStatusCode(404);
        }
        $this->tasksService->deleteSingle($task);
        return $response->setStatusCode(204);
    }

    public function deletePeriodic(string $id, Response $response)
    {
        $this->tasksService->assignUser(Auth::user());
        $task = $this->tasksService->getPeriodic($id);
        if ($task === null) {
            return $response->setStatusCode(404);
        }
        $this->tasksService->deleteSPeriodic($task);
        return $response->setStatusCode(204);
    }
}
