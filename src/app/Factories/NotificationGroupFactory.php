<?php

namespace App\Factories;

use App\Models\AlarmGroup;
use App\Models\NotificationGroup;
use App\Models\NotificationsType;
use App\Utils\TimeUtils;

abstract class NotificationGroupFactory
{
    /**
     * @param AlarmGroup $alarmGroup
     * @param int $time
     * @param NotificationsType[] $notificationTypes
     *
     * @return NotificationGroup
     */
    public static function create(
        AlarmGroup $alarmGroup, int $time, array $notificationTypes
    ): NotificationGroup
    {
        $notification = new NotificationGroup(
            ['time' => TimeUtils::roundToFullMinutes($time)]
        );
        $notification->alarm()
            ->associate($alarmGroup);
        $notification->save();
        foreach ($notificationTypes as $type) {
            $notification->types()
                ->attach($type);
        }
        $notification->save();
        return $notification;
    }
}
