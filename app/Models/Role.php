<?php

namespace App\Models;

use App\Services\Permissions\Guard;
use App\Services\Permissions\PermissionRegistrar;
use App\Traits\HasPermissions;
use App\Traits\RefreshesPermissionCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasPermissions;
    use RefreshesPermissionCache;

    protected $table = 'roles';

    protected $guarded = [];

    protected $fillable = [
        'title', 'name', 'can_not_delete'
    ];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->guarded[] = $this->primaryKey;
    }

    public function getTable()
    {
        return config('permission.table_names.roles', parent::getTable());
    }

    /**
     * @throws \Exception
     */
    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $params = ['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']];
        if (PermissionRegistrar::$teams) {
            if (array_key_exists(PermissionRegistrar::$teamsKey, $attributes)) {
                $params[PermissionRegistrar::$teamsKey] = $attributes[PermissionRegistrar::$teamsKey];
            } else {
                $attributes[PermissionRegistrar::$teamsKey] = app(PermissionRegistrar::class)->getPermissionsTeamId();
            }
        }
        if (static::findByParam($params)) {
            throw new \Exception('RoleAlreadyExists');
        }

        return static::query()->create($attributes);
    }

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotRole,
            PermissionRegistrar::$pivotPermission
        );
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_roles'),
            PermissionRegistrar::$pivotRole,
            config('permission.column_names.model_morph_key')
        );
    }

    /**
     * Find a role by its name and guard name.
     *
     * @param string $name
     * @param string|null $guardName
     * @return mixed
     * @throws \Exception
     */
    public static function findByName(string $name, $guardName = null)
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        if (!$role) {
            throw new \Exception('RoleDoesNotExist');
        }

        return $role;
    }

    /**
     * @throws \Exception
     */
    public static function findById(int $id, $guardName = null)
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam(['id' => $id, 'guard_name' => $guardName]);

        if (!$role) {
            throw new \Exception('RoleDoesNotExist');
        }

        return $role;
    }

    /**
     * Find or create role by its name (and optionally guardName).
     *
     * @param string $name
     * @param string|null $guardName
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public static function findOrCreate(string $name, $guardName = null)
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        if (!$role) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName] + (PermissionRegistrar::$teams ? [PermissionRegistrar::$teamsKey => app(PermissionRegistrar::class)->getPermissionsTeamId()] : []));
        }

        return $role;
    }

    protected static function findByParam(array $params = [])
    {
        $query = static::when(PermissionRegistrar::$teams, function ($q) use ($params) {
            $q->where(function ($q) use ($params) {
                $q->whereNull(PermissionRegistrar::$teamsKey)
                    ->orWhere(PermissionRegistrar::$teamsKey, $params[PermissionRegistrar::$teamsKey] ?? app(PermissionRegistrar::class)->getPermissionsTeamId());
            });
        });
        unset($params[PermissionRegistrar::$teamsKey]);
        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|Permission $permission
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function hasPermissionTo($permission): bool
    {
        if (config('permission.enable_wildcard_permission', false)) {
            return $this->hasWildcardPermission($permission, $this->getDefaultGuardName());
        }

        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByName($permission, $this->getDefaultGuardName());
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
        }

        if (!$this->getGuardNames()->contains($permission->guard_name)) {
            throw new \Exception('GuardDoesNotMatch');
        }

        return $this->permissions->contains('id', $permission->id);
    }
}
