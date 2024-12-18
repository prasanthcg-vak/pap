<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientGroupPartners extends Model
{
    protected $fillable = ['user_id', 'group_id'];
    protected $table = 'partner_client_groups';
    public function group()
    {
        return $this->belongsTo(ClientGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }   
}
