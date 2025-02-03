<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultStaff extends Model
{
    use HasFactory;

    protected $table = 'default_staffs';

    protected $fillable = [
        'default_staff_id',
    ];

    // Relationship: Each DefaultStaff belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class, 'default_staff_id');
    }
}
