<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignPartner extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description', 'is_active'];

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }
    protected $table = 'campaigns_partner';

    public function taskstatus() {
        return $this->belongsTo('App\Models\Status', 'status_id', 'id');
    }
}
