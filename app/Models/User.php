<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }


    public function hasRolePermission($permission)
    {
        $role = $this->roles()->first();

        if ($role && $role->permissions->contains('name', $permission)) {
            return true;
        }

        return false;
    }

    public function quoteTemplateItems() {
        return $this->hasMany('App\Models\QuoteTemplateItems', 'quote_id', 'id');
    }

    // public function roles() {
    //     return $this->belongsTo('App\Models\Roles', 'role_id', 'id');
    // }


}
