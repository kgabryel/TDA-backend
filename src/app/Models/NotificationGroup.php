<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationGroup extends Model
{
    protected $table = 'notifications_groups';
    protected $fillable = [
        'time'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function alarm()
    {
        return $this->belongsTo(AlarmGroup::class, 'alarm_id', 'id');
    }

    public function types()
    {
        return $this->belongsToMany(
            NotificationsType::class,
            'notifications_groups_types',
            'group_id',
            'type_id'
        );
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
