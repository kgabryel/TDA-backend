<?php

namespace App\Utils;

use App\Models\Alarm;
use App\Models\AlarmGroup;
use App\Models\TaskGroup;

abstract class TasksUtils
{
    public static function assignAlarm(TaskGroup $taskGroup, AlarmGroup $alarmGroup): void
    {
        $alarmGroup->task()
            ->associate($taskGroup);
        $alarmGroup->save();
        $taskGroup->save();
        $tasks = $taskGroup->tasks()
            ->get();
        $alarms = $alarmGroup->alarms()
            ->get();
        /**
         * @var  $index
         * @var Alarm $alarm
         */
        foreach ($alarms as $index => $alarm) {
            $alarm->task()
                ->associate($tasks[$index]);
            $alarm->save();
        }
    }
}
