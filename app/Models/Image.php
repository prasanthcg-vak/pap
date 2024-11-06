<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    // Optional: specify fillable fields if needed
    protected $fillable = ['file_name', 'path'];
}
