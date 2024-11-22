<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ClientGroup extends Model
{
    protected $fillable = ['name', 'client_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function users()
    {
        return $this->hasMany(ClientUser::class, 'group_id');
    }
}