<?php

namespace App\Console\Commands\Notifications;

use App\Factories\AlarmFactory;
use App\Factories\NotificationBuffFactory;
use App\Factories\NotificationFactory;
use App\Factories\TaskFactory;
use App\Models\AlarmGroup;
use App\Models\NotificationGroup;
use App\Models\TaskGroup;
use App\Models\User;
use App\Utils\DateUtils;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreatePeriodicTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:periodic-create';
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
        $tasks = TaskGroup::whereNull('stop')
            ->orWhere('stop', '>=', $start)
            ->get();
        /** @var TaskGroup $task */
        foreach ($tasks as $task) {
            /** @var User $user */
            $user = $task->user()
                ->first();
            $subTasks = [];
            $alarms = [];
            $dates = DateUtils::getDates($task->interval, $task->interval_type, $start, $stop);
            foreach ($dates as $date) {
                $subTasks[] = TaskFactory::create(
                    [
                        'title' => $task->title,
                        'content' => $task->content,
                    ],
                    $user,
                    null,
                    $task,
                    $date
                );
            }
            /** @var AlarmGroup $alarmGroup */
            $alarmGroup = $task->alarm()
                ->first();
            if ($alarmGroup === null) {
                continue;
            }
            $notificationGroup = $alarmGroup->notificationsGroups()
                ->get();
            foreach ($dates as $date) {
                $alarm = AlarmFactory::create(
                    [
                        'title' => $alarmGroup->title,
                        'content' => $alarmGroup->content,
                    ],
                    $user,
                    $alarmGroup,
                    $date
                );
                /** @var NotificationGroup $group */
                foreach ($notificationGroup as $group) {
                    $notificationTypes = $group->types()
                        ->get()
                        ->toArray();
                    $time = DateUtils::modifyDate($date, $group->time);
                    $notification = NotificationFactory::create(
                        $time,
                        $alarm,
                        $notificationTypes,
                        $group
                    );
                    $alarms[] = $alarm;
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
            foreach ($subTasks as $index => $subTask) {
                $subTask->alarm()
                    ->associate($alarms[$index]);
                $subTask->save();
            }
        }
        return 0;
    }
}
