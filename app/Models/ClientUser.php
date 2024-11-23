<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientUser extends Model
{
    protected $fillable = ['user_id', 'client_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
