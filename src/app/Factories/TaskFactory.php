<?php

namespace App\Factories;

use App\Models\Task;
use App\Models\TaskGroup;
use App\Models\TaskStatus;
use App\Models\User;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

abstract class TaskFactory
{
    public static function create(
        array $data, User $user, ?Task $mainTask, ?TaskGroup $taskGroup, ?Carbon $date
    ): Task
    {
        $task = new Task(
            [
                'id' => Uuid::uuid4()
                    ->toString(),
                'title' => $data['title'],
                'content' => $data['content'],
                'date' => $date,
            ]
        );
        $task->user()
            ->associate($user);
        if ($date === null || $date >= Carbon::now()) {
            $task->status()
                ->associate(TaskStatus::find(TaskStatus::TASK_TO_DO_ID));
        } else {
            $task->status()
                ->associate(TaskStatus::find(TaskStatus::TASK_UNDONE_ID));
        }
        if ($mainTask !== null) {
            $task->mainTask()
                ->associate($mainTask);
        }
        if ($taskGroup !== null) {
            $task->group()
                ->associate($taskGroup);
        }
        $task->save();
        return $task;
    }
}
