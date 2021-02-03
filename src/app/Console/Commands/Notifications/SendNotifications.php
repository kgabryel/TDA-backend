<?php

namespace App\Console\Commands\Notifications;

use App\Models\NotificationBuff;
use App\Services\Notifications\SendEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notifications = NotificationBuff::where('locked', '=', false)
            ->where(
                'time',
                '<=',
                Carbon::now()
                    ->toDateTimeString()
            )
            ->get();
        NotificationBuff::whereIn(
            'id',
            $notifications->pluck('id')
                ->toArray()
        )
            ->update(['locked' => true]);
        foreach ($notifications as $notification) {
            SendEmail::sendEmail($notification);
            $notification->delete();
        }
        return 0;
    }
}
