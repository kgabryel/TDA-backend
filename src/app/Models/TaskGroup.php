<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TaskGroup extends Model
{
    use Uuid;

    protected $table = 'tasks_groups';
    protected $fillable = [
        'id',
        'title',
        'content',
        'start',
        'stop',
        'interval',
        'interval_type',
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

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'group_id');
    }

    public function alarm(): HasOne
    {
        return $this->hasOne(AlarmGroup::class, 'task_id', 'id');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'date' => $this->date,
            'status' => $this->status_id,
            'periodic' => true,
            'interval' => $this->interval,
            'intervalType' => $this->interval_type,
            'start' => $this->start,
            'stop' => $this->stop,
            'parentId' => null,
            'tasks' => $this->tasks()
                ->get(),
            'alarm' => $this->alarm()
                ->first()
        ];
    }
}
