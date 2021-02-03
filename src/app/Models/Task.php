<?php

namespace App\Models;

use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use Uuid;

    protected $fillable = [
        'id',
        'title',
        'content',
        'date'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alarm()
    {
        return $this->hasOne(Alarm::class, 'task_id', 'id');
    }

    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id', 'id');
    }

    public function mainTask()
    {
        return $this->belongsTo(Task::class, 'parent_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(TaskGroup::class, 'group_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id', 'id');
    }

    public function toArray()
    {
        $task = [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'date' => $this->date,
            'status' => $this->status_id,
            'periodic' => false,
            'parentId' => $this->parent_id,
            'subtasks' => $this->subtasks()
                ->pluck('id')
        ];
        $alarm = $this->alarm()
            ->first();
        if ($alarm !== null) {
            $task['alarm'] = $alarm->id;
        } else {
            $task['alarm'] = null;
        }
        return $task;
    }
}
