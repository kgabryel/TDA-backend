<?php

namespace App\Utils;

use App\Models\NotificationsType;

abstract class AlarmsUtils
{
    /**
     * @param int[] $types
     *
     * @return NotificationsType[]
     */
    public static function getNotificationTypes(array $types): array
    {
        $notificationTypes = [];
        foreach ($types as $type) {
            $notificationTypes[] = NotificationsType::find($type);
        }
        return $notificationTypes;
    }
}
