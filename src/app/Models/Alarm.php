<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Alarm extends Model
{
    use Uuid;

    protected $fillable = [
        'id',
        'title',
        'content',
        'checked',
        'date'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function group()
    {
        return $this->belongsTo(AlarmGroup::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function toArray()
    {
        $task = $this->task()
            ->first();
        if ($task !== null) {
            $task = $task->id;
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'checked' => $this->checked,
            'notifications' => $this->notifications()
                ->orderBy('time')
                ->get(),
            'periodic' => false,
            'date' => $this->date,
            'task' => $task
        ];
    }
}
