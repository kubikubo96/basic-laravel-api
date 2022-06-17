<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'title', 'name', 'can_not_delete'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions', 'permission_id','user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }
}
