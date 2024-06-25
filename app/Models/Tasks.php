<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;
    protected $table = 'tasks';
    protected $fillable = [
        'users_id',
        'task',
        'due_date',
        'reminder_datetime',
        'is_important',
        'is_completed'
    ];
}
