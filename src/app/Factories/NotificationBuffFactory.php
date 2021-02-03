<?php

namespace App\Factories;

use App\Models\Notification;
use App\Models\NotificationBuff;
use App\Models\User;
use Carbon\Carbon;

abstract class NotificationBuffFactory
{
    public static function create(
        array $data, Carbon $time, User $user, Notification $notification
    ): NotificationBuff
    {
        $notificationBuff = new NotificationBuff(
            [
                'title' => $data['title'],
                'content' => $data['content'],
                'time' => $time
            ]
        );
        $notificationBuff->user()
            ->associate($user);
        $notificationBuff->notification()
            ->associate($notification);
        $notificationBuff->save();
        return $notificationBuff;
    }
}
