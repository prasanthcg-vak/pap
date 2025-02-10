<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    // Optional: specify fillable fields if needed
    protected $fillable = ['file_name', 'path'];

    public function campaign()
    {
        return $this->belongsTo(Campaigns::class);
    }

    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }

    public function campaigns()
    {
        return $this->belongsTo(Campaigns::class, 'campaign_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'image_id');
    }
    public function versioning()
    {
        return $this->hasMany(TaskVersion::class, 'asset_id');
    }

}
