<?php

namespace App\Services\Notifications;

use App\Mail\Notification;
use App\Models\NotificationBuff;
use Illuminate\Support\Facades\Mail;

class SendEmail
{

    public static function sendEmail(NotificationBuff $notification):void{
        $email = new Notification($notification->content);
        $email->subject($notification->title);
        Mail::to($notification->user()->first()->email)
            ->send($email);
    }

}
