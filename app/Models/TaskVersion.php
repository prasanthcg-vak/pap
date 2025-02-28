<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskVersion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_id',
        'staff_id',
        'versioning_status_id',
        'comment_id',
        'description',
        'asset_id'
    ];

    public function task()
    {
        return $this->belongsTo(Tasks::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function versionStatus()
    {
        return $this->belongsTo(VersioningStatus::class, 'versioning_status_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class,'comment_id');
    }

    public function asset()
    {
        return $this->belongsTo(TaskImage::class);
    }
    public function images()
    {
        return $this->belongsTo(TaskImage::class, 'asset_id');
    }
}
