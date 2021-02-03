<?php

namespace App\Factories;

use App\Models\AlarmGroup;
use App\Models\TaskGroup;
use App\Models\User;
use Ramsey\Uuid\Uuid;

abstract class AlarmGroupFactory
{
    public static function create(array $data, User $user, ?TaskGroup $taskGroup): AlarmGroup
    {
        $alarm = new AlarmGroup(
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
        $alarm->user()
            ->associate($user);
        $alarm->save();
        if ($taskGroup !== null) {
            $alarm->task()
                ->associate($taskGroup);
        }
        $alarm->save();
        return $alarm;
    }
}
