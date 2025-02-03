<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignStaff extends Model
{
    use HasFactory;
    
    protected $fillable = ['campaign_id', 'staff_id'];

    protected $table = 'campaign_staff';

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    public function campaign()
    {
        return $this->belongsTo(Campaigns::class, 'campaigns_id');
    }
}
