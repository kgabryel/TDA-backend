<?php

namespace App\Console\Commands\Notifications;

use App\Factories\AlarmFactory;
use App\Factories\NotificationBuffFactory;
use App\Factories\NotificationFactory;
use App\Models\AlarmGroup;
use App\Utils\DateUtils;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreatePeriodicAlarms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:periodic-create';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stop = Carbon::now()
            ->addMonths(2)
            ->endOfMonth();
        $start = Carbon::now()
            ->addMonths(2)
            ->startOfMonth();
        $alarms = AlarmGroup::whereNull('task_id')
            ->where(
                function($query) use ($start) {
                    $query->whereNull('stop')
                        ->orWhere('stop', '>=', $start);
                }
            )
            ->get();
        foreach ($alarms as $alarmGroup) {
            $user = $alarmGroup->user()
                ->first();
            $alarmStart = $alarmGroup->start;
            $interval = $alarmGroup->interval;
            $intervalType = $alarmGroup->interval_type;
            $alarmStart=DateUtils::addInterval($interval, $intervalType, $alarmStart);
            $groups = $alarmGroup->notificationsGroups()
                ->get();
            while ($alarmStart < $stop) {
                $alarm = AlarmFactory::create(
                    [
                        'title' => $alarmGroup->title,
                        'content' => $alarmGroup->content
                    ],
                    $user,
                    $alarmGroup,
                    null
                );
                foreach ($groups as $group) {
                    $notificationTypes = $group->types()
                        ->get();
                    $time = $group->time;
                    $notificationTime = $alarmStart->copy()
                        ->addSeconds($time);
                    $notification = NotificationFactory::create(
                        $notificationTime->toDateTimeString(),
                        $alarm,
                        $notificationTypes,
                        $group
                    );
                    if ($notificationTime <= $stop) {
                        NotificationBuffFactory::create(
                            [
                                'title' => $alarmGroup->title,
                                'content' => $alarmGroup->content,
                            ],
                            $time,
                            $user,
                            $notification
                        );
                    }
                }
                $alarmStart=DateUtils::addInterval($interval, $intervalType, $alarmStart);
            }
        }
        return 0;
    }
}
