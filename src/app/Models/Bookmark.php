<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = [
        'title',
        'content',
        'href',
        'icon',
        'background_color',
        'text_color',
        'assigned_to_dashboard'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'href' => $this->href,
            'icon' => $this->icon,
            'backgroundColor'=>$this->background_color,
            'textColor'=>$this->text_color,
            'assignedToDashboard' => $this->assigned_to_dashboard
        ];
    }
}
