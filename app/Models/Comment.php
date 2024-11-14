<?php
// app/Models/Comment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['tasks_id', 'parent_id', 'content','created_by'];
    protected $table = 'comments';

    // Relationship to get task
    public function task()
    {
        return $this->belongsTo(Tasks::class);
    }

    // Relationship to get replies
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // Relationship to get parent comment
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
}
