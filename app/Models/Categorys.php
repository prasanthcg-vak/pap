<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorys extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_name',
        'category_description',
        'is_active',
    ];


    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }

}
