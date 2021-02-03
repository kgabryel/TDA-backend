<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NotificationsType extends Model
{
    public $timestamps = false;
    protected $table = 'available_notifications_types';
    protected $fillable = [
        'name',
        'color'
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color
        ];
    }
}
