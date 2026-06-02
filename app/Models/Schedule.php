<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'day_of_week',
        'subject',
        'start_time',
        'end_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    protected $appends = [
        'top',
        'height',
    ];

    public function getTopAttribute()
    {
        $hour = (int) substr($this->start_time, 0, 2);
        $minute = (int) substr($this->start_time, 3, 2);

        return (($hour - 7) * 60) + $minute;
    }

    public function getHeightAttribute()
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);

        return ($end - $start) / 60;
    }
}
