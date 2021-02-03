<?php

namespace App\Console\Commands\Notifications;

use App\Models\Notification;
use App\Models\NotificationBuff;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InsertToBuff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:insert';
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
        $start = Carbon::now()
            ->addMonth()
            ->startOfMonth();
        $stop = Carbon::now()
            ->addMonth()
            ->endOfMonth();
        $notifications = Notification::whereBetween(
            'time',
            [
                $start,
                $stop
            ]
        )
            ->get();
        foreach ($notifications as $notification) {
            $alarm = $notification->alarm()
                ->first();
            NotificationBuff::updateOrCreate(
                [
                    'title' => $alarm->title,
                    'content' => $alarm->content,
                    'time' => $notification->time,
                    'user_id' => $alarm->user_id,
                    'notification_id' => $notification->id
                ],
                ['notification_id' => $notification->id]
            );
        }
        return 0;
    }
}
