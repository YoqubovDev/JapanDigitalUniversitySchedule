<?php

namespace App\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Role extends Model
{
    /** @use HasFactory<RoleFactory> */
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
    ];
}
