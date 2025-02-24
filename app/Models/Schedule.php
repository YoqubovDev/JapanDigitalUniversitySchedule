<?php

namespace App\Models;

use Database\Factories\ScheduleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /** @use HasFactory<ScheduleFactory> */
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'group_id',
        'subject_id',
        'teacher_id',
        'room_id',
        'date',
        'start_time',
        'end_time',
        ];
}
