<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SharedAsset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shared_assets'; // Explicitly defining table name

    protected $fillable = [
        'task_id',
        'asset_id',
        'partner_id',
        'start_date',
        'end_date',
    ];

    public function task()
    {
        return $this->belongsTo(Tasks::class);
    }
    public function taskImage()
    {
        return $this->belongsTo(TaskImage::class, 'asset_id', 'id');
    }

    public function partner()
    {
        return $this->belongsTo(User::class);
    }
}
