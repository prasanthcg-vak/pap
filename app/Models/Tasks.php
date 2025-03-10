<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'campaign_id',
        'name',
        'description',
        'date_required',
        'task_urgent',
        'category_id',
        'size_width',
        'size_height',
        'size_measurement',
        'status_id',
        'asset_id',
        'is_active',
        'partner_id',
        'image_id',
        'deleted_by',
        'marked_for_deletion',
    ];

    protected $dates = ['deleted_at'];

    public function campaign()
    {
        return $this->belongsTo(Campaigns::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    public function task_status()
    {
        return $this->belongsTo(TaskStatus::class,'status_id');
    }

    public function categorys()
    {
        return $this->belongsTo(Category::class);
    }

    public function asset()
    {
        return $this->belongsTo(AssetType::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id'); // Get only main comments
    }

    public function image()
    {
        return $this->belongsTo(TaskImage::class, 'image_id');
    }
    public function task_comments()
    {
        return $this->hasMany(Comment::class, 'task_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // public function partner()
    // {
    //     return $this->belongsTo(Partner::class, 'partner_id');
    // }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function taskStaff()
    {
        return $this->hasMany(TaskStaff::class, 'task_id')->with('staff');
    }
    public function taskImage()
    {
        return $this->hasMany(TaskImage::class, 'task_id')->with('sharedAssets');
    }


}
