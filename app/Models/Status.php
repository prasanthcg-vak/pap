<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = "status";
    protected $fillable = ['name', 'description', 'is_active'];

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }
}
