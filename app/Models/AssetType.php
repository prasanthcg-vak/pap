<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    use HasFactory;

    protected $table = 'asset_types';

    protected $fillable = [
        'type_name',
        'type_description',
        'is_active',
    ];

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }
}
