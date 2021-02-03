<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    public const TASK_TO_DO_ID = 1;
    public const TASK_DONE_ID = 3;
    public const TASK_UNDONE_ID = 4;
    protected $table = 'tasks_statuses';
    protected $fillable = [
        'name',
        'color',
        'icon'
    ];
    public $timestamps = false;

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'icon' => $this->icon
        ];
    }
}
