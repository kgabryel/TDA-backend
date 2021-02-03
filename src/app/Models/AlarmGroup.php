<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlarmGroup extends Model
{
    use Uuid;

    protected $table = 'alarms_groups';
    protected $fillable = [
        'id',
        'title',
        'content',
        'start',
        'stop',
        'interval',
        'interval_type'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'start' => 'date',
        'stop' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notificationsGroups()
    {
        return $this->hasMany(NotificationGroup::class, 'alarm_id');
    }

    public function alarms()
    {
        return $this->hasMany(Alarm::class, 'group_id');
    }

    public function task()
    {
        return $this->belongsTo(TaskGroup::class, 'task_id', 'id');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'interval' => $this->interval,
            'intervalType' => $this->interval_type,
            'start' => $this->start,
            'stop' => $this->stop,
            'task' => null,
            'alarms' => $this->alarms()
                ->get(),
            'periodic' => true,
            'notifications' => $this->notificationsGroups()
                ->orderBy('time')
                ->get()
        ];
    }
}
