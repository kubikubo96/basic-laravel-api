<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends BaseRepository
{
    public function getModel(): string
    {
        return Role::class;
    }
}
