<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'time',
        'checked',
        'date'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'time' => 'datetime'
    ];

    public function types()
    {
        return $this->belongsToMany(
            NotificationsType::class,
            'notifications_types',
            'notification_id',
            'type_id'
        );
    }

    public function alarm()
    {
        return $this->belongsTo(Alarm::class);
    }

    public function notificationBuff()
    {
        return $this->hasOne(NotificationBuff::class);
    }
    public function group()
    {
        return $this->belongsTo(NotificationGroup::class);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'time' => $this->time,
            'checked' => $this->checked,
            'types' => $this->types()
                ->pluck('id'),
        ];
    }
}
