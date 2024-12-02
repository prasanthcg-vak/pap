<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'is_active',
        'profile_picture',
        'contact',
        'pcode',
        'client_id',
        'group_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function hasRolePermission($permission)
    {
        $role = $this->roles()->first();

        return $role && $role->permissions->contains('name', $permission);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function clientGroups()
    {
        return $this->belongsToMany(ClientGroup::class, 'client_group_user', 'user_id', 'client_group_id');
    }

    // Belongs-to relationship with Group
    public function group()
    {
        return $this->belongsTo(ClientGroup::class, 'group_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
