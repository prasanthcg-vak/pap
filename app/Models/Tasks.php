<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'campaign_id', 'name', 'description', 'date_required', 'task_urgent', 
        'category_id', 'size_width', 'size_height', 'status_id', 'is_active'
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
    public function categorys()
    {
        return $this->belongsTo(Category::class);
    }
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id'); // Get only main comments
    }
    
}
