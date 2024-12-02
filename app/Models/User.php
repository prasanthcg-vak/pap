<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Fillable fields for mass assignment
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
        'group_id',
    ];

    // Fields hidden from serialization
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Field casting for attributes
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Define the many-to-many relationship with roles.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Check if the user has a specific permission via their roles.
     *
     * @param string $permission
     * @return bool
     */
    public function hasRolePermission($permission)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    /**
     * Define the belongs-to relationship with a single role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Define the many-to-many relationship with client groups.
     */
    public function clientGroups()
    {
        return $this->belongsToMany(ClientGroup::class, 'client_group_user', 'user_id', 'client_group_id');
    }

    /**
     * Define the belongs-to relationship with a single group.
     */
    public function group()
    {
        return $this->belongsTo(ClientGroup::class, 'group_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Check if the user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->roles()->where('name', 'Super Admin')->exists();
    }

    /**
     * Accessor for user display name.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->name ?: $this->username;
    }

    /**
     * Mutator to hash passwords on setting.
     *
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Scope to filter active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Generate a unique username from the user's name.
     *
     * @param string $name
     * @return string
     */
    public static function generateUsernameFromName($name)
    {
        return Str::slug($name) . rand(1000, 9999);
    }
}
