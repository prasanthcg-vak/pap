<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpersonationLog extends Model
{
    use HasFactory;

    protected $fillable = ['impersonator_id', 'impersonated_user_id', 'started_at', 'ended_at'];

    public function impersonator()
    {
        return $this->belongsTo(User::class, 'impersonator_id');
    }

    public function impersonatedUser()
    {
        return $this->belongsTo(User::class, 'impersonated_user_id');
    }
}
