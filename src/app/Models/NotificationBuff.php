<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationBuff extends Model
{
    protected $table = 'notifications_buff';
    public $timestamps = false;
    protected $casts = [
        'time' => 'datetime'
    ];
    protected $fillable = [
        'title',
        'content',
        'time',
        'notification_id',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }
}
