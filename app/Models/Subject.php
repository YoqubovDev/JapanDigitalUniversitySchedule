<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Subject extends Model
{

    use HasFactory,HasApiTokens;
    public $timestamps = true;
    protected $fillable = [
        'name',
        ];
    protected $table = 'subjects';

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_subject', 'subject_id', 'group_id')
            ->withTimestamps();
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'group_subject', 'group_id', 'subject_id')
            ->withTimestamps();
    }
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'subject_teacher', 'subject_id', 'user_id')
            ->withTimestamps();
    }
}
