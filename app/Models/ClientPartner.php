<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientPartner extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Add the 'deleted_at' column to the $dates property
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'client_id',
        'partner_id',
    ];

    // Define the relationship for the client
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Define the relationship for the partner
    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}
