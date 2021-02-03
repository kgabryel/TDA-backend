<?php

namespace App\Services;

namespace App\Services;

use App\Factories\AlarmFactory;
use App\Factories\AlarmGroupFactory;
use App\Factories\NotificationBuffFactory;
use App\Factories\NotificationFactory;
use App\Factories\NotificationGroupFactory;
use App\Models\Alarm;
use App\Models\AlarmGroup;
use App\Models\Notification;
use App\Models\TaskGroup;
use App\Models\User;
use App\Utils\AlarmsUtils;
use App\Utils\DateUtils;
use Carbon\Carbon;

class AlarmsService
{
    private User $user;

    public function assignUser(User $user): void
    {
        $this->user = $user;
    }

    public function deleteSingleAlarm(Alarm $alarm): void
    {
        $alarm->delete();
    }

    public function deleteSPeriodicAlarm(AlarmGroup $alarm): void
    {
        $alarm->delete();
    }

    public function deleteNotification(Notification $notification): Alarm
    {
        $notification->delete();
        return $notification->alarm()
            ->first();
    }

    public function checkAlarm(Alarm $alarm): void
    {
        $alarm->checked = true;
        $alarm->save();
        foreach (
            $alarm->notifications()
                ->get() as $notification
        ) {
            $this->checkNotification($notification);
        }
    }

    public function uncheckAlarm(Alarm $alarm): bool
    {
        $checkResult = false;
        foreach (
            $alarm->notifications()
                ->get() as $notification
        ) {
            if ($notification->time <= Carbon::now()) {
                continue;
            }
            if ($this->uncheckNotification($notification)) {
                $checkResult = true;
            }
        }
        return $checkResult;
    }

    public function checkNotification(Notification $notification): void
    {
        $notification->checked = true;
        $notification->save();
        $alarm = $notification->alarm()
            ->first();
        if (
            $alarm->notifications()
                ->where('checked', '=', false)
                ->count() === 0
        ) {
            $alarm->checked = true;
            $alarm->save();
        }
        $notificationBuff = $notification->notificationBuff()
            ->first();
        if ($notificationBuff !== null) {
            $notificationBuff->delete();
        }
    }

    public function uncheckNotification(Notification $notification): bool
    {
        if ($notification->time <= Carbon::now()) {
            return false;
        }
        $notification->checked = false;
        $alarm = $notification->alarm()
            ->first();
        NotificationBuffFactory::create(
            [
                'title' => $alarm->title,
                'content' => $alarm->content
            ],
            $notification->time,
            $this->user,
            $notification
        );
        $notification->save();
        $alarm->checked = false;
        $alarm->save();
        return true;
    }

    public function getSingleAlarm(string $id): ?Alarm
    {
        return Alarm::where('user_id', '=', $this->user->id)
            ->where('id', '=', $id)
            ->first();
    }

    public function getPeriodicAlarm(string $id): ?AlarmGroup
    {
        return AlarmGroup::where('user_id', '=', $this->user->id)
            ->where('id', '=', $id)
            ->first();
    }
    public function getOneNotification(int $id): ?Notification
    {
        $notification = Notification::find($id);
        if ($notification === null) {
            return null;
        }
        $alarm = $notification->alarm()
            ->first();
        if (
            $alarm->user()
                ->first()->id === $this->user->id
        ) {
            return $notification;
        }
        return null;
    }

    public function getAll()
    {
        $alarms = Alarm::where('user_id', '=', $this->user->id)
            ->whereNull('group_id')
            ->get();
        return $alarms->concat(
            AlarmGroup::where('user_id', '=', $this->user->id)
                ->get()
        )
            ->sort(
                function($a, $b) {
                    if ($a->created_at == $b->created_at) {
                        return 0;
                    }
                    return ($a->created_at < $b->created_at) ? -1 : 1;
                }
            )
            ->values();
    }

    public function createSingle(array $data): Alarm
    {
        $alarm = AlarmFactory::create($data, $this->user, null, null);
        $notificationTypes = AlarmsUtils::getNotificationTypes($data['notificationTypes']);
        foreach ($data['notifications'] ?? [] as $notification) {
            $time = new Carbon($notification);
            $notification = NotificationFactory::create($time, $alarm, $notificationTypes, null);
            NotificationBuffFactory::create($data, $time, $this->user, $notification);
        }
        return $alarm;
    }

    /**
     * @param array $data
     * @param Carbon[] $dates
     *
     * @return AlarmGroup
     */
    public function createPeriodic(array $data, array $dates, ?TaskGroup $taskGroup): AlarmGroup
    {
        $alarmGroup = AlarmGroupFactory::create($data, $this->user, $taskGroup);
        $notificationTypes = AlarmsUtils::getNotificationTypes($data['notificationTypes']);
        $groups = [];
        foreach ($data['notifications'] as $notification) {
            $groups[] = NotificationGroupFactory::create(
                $alarmGroup,
                $notification,
                $notificationTypes
            );
        }
        foreach ($dates as $date) {
            $alarm = AlarmFactory::create($data, $this->user, $alarmGroup, $date);
            foreach ($groups as $group) {
                $time = DateUtils::modifyDate($date, $group->time);
                $notification = NotificationFactory::create(
                    $time,
                    $alarm,
                    $notificationTypes,
                    $group
                );
                NotificationBuffFactory::create($data, $time, $this->user, $notification);
            }
        }
        return $alarmGroup;
    }
}
