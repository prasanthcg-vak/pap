<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    protected $table = "task_status";
    protected $fillable = ['name', 'description', 'is_active'];

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }
}
