<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ClientGroup extends Model
{
    protected $fillable = ['name', 'description'];

    
    public function users()
    {
        return $this->belongsToMany(User::class, 'client_group_user');
    }
}