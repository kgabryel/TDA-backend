<?php

namespace App\Factories;

use App\Models\Alarm;
use App\Models\Notification;
use App\Models\NotificationGroup;
use App\Models\NotificationsType;
use Carbon\Carbon;

abstract class NotificationFactory
{
    /**
     * @param Carbon $time
     * @param Alarm $alarm
     * @param NotificationsType[] $notificationTypes
     * @param NotificationGroup|null $notificationGroup
     *
     * @return Notification
     */
    public static function create(
        Carbon $time, Alarm $alarm, array $notificationTypes, ?NotificationGroup $notificationGroup
    ): Notification
    {
        $afterTime = $time < Carbon::now();
        $notification = new Notification(
            [
                'time' => $time->startOfMinute()
                    ->toDateTimeString(),
                'checked' => $afterTime
            ]
        );
        $notification->alarm()
            ->associate($alarm);
        if ($afterTime) {
            $alarm->checked = true;
            $alarm->save();
        }
        if ($notificationGroup !== null) {
            $notification->group()
                ->associate($notificationGroup);
        }
        $notification->save();
        foreach ($notificationTypes as $type) {
            $notification->types()
                ->attach($type);
        }
        return $notification;
    }
}
