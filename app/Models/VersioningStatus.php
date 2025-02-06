<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersioningStatus extends Model
{
    protected $table = "versioning_status";
    protected $fillable = ['status'];

    // public function tasks()
    // {
    //     return $this->hasMany(Tasks::class);
    // }
}
