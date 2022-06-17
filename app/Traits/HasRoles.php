<?php

namespace App\Traits;

trait HasRoles
{
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        if (is_int($role)) {
            return $this->roles->contains('id', $role);
        }

        if (is_array($role)) {
            foreach ($role as $item) {
                if ($this->hasRole($item)) {
                    return true;
                }
            }
            return false;
        }

        return false;
    }
}
