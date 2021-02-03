<?php

namespace App\Factories;

use App\Models\Alarm;
use App\Models\AlarmGroup;
use App\Models\User;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

abstract class AlarmFactory
{
    public static function create(array $data, User $user, ?AlarmGroup $alarmGroup, ?Carbon $date
    ): Alarm
    {
        $alarm = new Alarm(
            [
                'id' => Uuid::uuid4()
                    ->toString(),
                'title' => $data['title'],
                'content' => $data['content'],
                'checked' => false,
                'date' => $date
            ]
        );
        $alarm->user()
            ->associate($user);
        if ($alarmGroup !== null) {
            $alarm->group()
                ->associate($alarmGroup);
        }
        $alarm->save();
        return $alarm;
    }
}
