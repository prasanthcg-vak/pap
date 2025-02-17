<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskImage extends Model
{
    protected $table = 'task_images';

    // Optional: specify fillable fields if needed
    protected $fillable = ['file_name', 'path'];

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
    public function sharedAssets()
    {
        return $this->hasMany(SharedAsset::class, 'asset_id', 'id');
    }
    

}
