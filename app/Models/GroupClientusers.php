<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupClientUsers extends Model
{
    use HasFactory;
    // use SoftDeletes;

    // Add the 'deleted_at' column to the $dates property
    protected $dates = ['deleted_at'];
    protected $table = 'group_clientusers';

    protected $fillable = [
        'group_id',
        'clientuser_id'
    ];

    // Define the relationship for the group
    public function group()
    {
        return $this->belongsTo(ClientGroup::class, 'group_id');
    }

    // Define the relationship for the client user
    public function client_user()
    {
        return $this->belongsTo(User::class, 'clientuser_id');
    }
}
