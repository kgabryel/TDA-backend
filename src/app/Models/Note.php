<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'title',
        'content',
        'background_color',
        'text_color',
        'assigned_to_dashboard'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'backgroundColor' => $this->background_color,
            'textColor' => $this->text_color,
            'date' => Carbon::parse($this->created_at)
                ->format('Y-m-d H:i:s'),
            'assignedToDashboard' => $this->assigned_to_dashboard
        ];
    }
}
