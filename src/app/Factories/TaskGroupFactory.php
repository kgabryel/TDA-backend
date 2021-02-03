<?php

namespace App\Factories;

use App\Models\TaskGroup;
use App\Models\User;
use Ramsey\Uuid\Uuid;

abstract class TaskGroupFactory
{
    public static function create(array $data, User $user): TaskGroup
    {
        $task = new TaskGroup(
            [
                'id' => Uuid::uuid4()
                    ->toString(),
                'title' => $data['title'],
                'content' => $data['content'],
                'start' => $data['start'],
                'stop' => $data['stop'],
                'interval' => $data['interval'],
                'interval_type' => $data['intervalType']
            ]
        );
        $task->user()
            ->associate($user);
        $task->save();
        return $task;
    }
}
