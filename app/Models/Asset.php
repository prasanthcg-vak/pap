<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_name',
        'asset_description',
        'is_active',
    ];

    // Define the relationship to the Task model
    public function task()
    {
        return $this->belongsTo(Tasks::class);
    }
}
