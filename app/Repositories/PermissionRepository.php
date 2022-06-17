<?php

namespace App\Repositories;

use App\Models\Permission;

class PermissionRepository extends BaseRepository
{
    public function getModel(): string
    {
        return Permission::class;
    }
}
