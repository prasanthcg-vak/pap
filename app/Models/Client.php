<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'description', 'is_active','logo'];

    public function groups()
    {
        return $this->hasMany(ClientGroup::class);
    }

    public function users()
    {
        return $this->hasMany(ClientUser::class);
    }
}
