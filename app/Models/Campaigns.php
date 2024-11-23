<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model
{
    use HasFactory;
    protected $table = 'campaigns';
    protected $fillable = ['name', 'description', 'is_active','due_date','status_id','client_id','client_group_id'];

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }

    public function taskstatus() {
        return $this->belongsTo('App\Models\Status', 'status_id', 'id');
    }
    

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'campaign_id');
    }
}
