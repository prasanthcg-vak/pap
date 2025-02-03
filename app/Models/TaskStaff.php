<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskStaff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'task_staffs'; // Explicitly define the table name

    protected $fillable = [
        'task_id',
        'staff_id',
    ];

    /**
     * Relationship with Task Model
     */
    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }

    /**
     * Relationship with User Model (Assuming Staff is a User)
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
