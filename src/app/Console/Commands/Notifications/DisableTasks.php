<?php

namespace App\Console\Commands\Notifications;

use App\Factories\AlarmFactory;
use App\Factories\NotificationBuffFactory;
use App\Factories\NotificationFactory;
use App\Models\AlarmGroup;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Utils\DateUtils;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DisableTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:disable';
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
        $tasks = Task::whereDate('date', '=', Carbon::now()->addDays(-1))
            ->get();
        $undoneStatus = TaskStatus::find(TaskStatus::TASK_UNDONE_ID);
        /** @var Task $task */
        foreach ($tasks as $task) {
            $task->status()
                ->associate($undoneStatus);
            $task->save();
        }
        return 0;
    }
}
