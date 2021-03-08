<?php

namespace Modules\Admin\Traits;

use Illuminate\Support\Collection;

trait RolePermissionHelpers
{
    /**
     * 关联的角色和权限, 是否只是 id 数组.
     */
    public bool $onlyRolePermissionIds = false;

    /**
     * 关联的角色和权限，只包含 slug.
     */
    public bool $onlyRolePermissionSlugs = false;

    /**
     * 获取所有权限，包括角色中的.
     */
    public bool $gatherAllPermissions = false;

    public function onlyRolePermissionIds($yes = true)
    {
        $this->onlyRolePermissionIds = $yes;

        return $this;
    }

    public function onlyRolePermissionSlugs($yes = true)
    {
        $this->onlyRolePermissionSlugs = $yes;

        return $this;
    }

    public function gatherAllPermissions($yes = true): RolePermissionHelpers
    {
        $this->gatherAllPermissions = $yes;

        return $this;
    }

    public function getRoles(): Collection
    {
        if ($this->onlyRolePermissionIds) {
            return $this->roles()->pluck('id');
        } elseif ($this->onlyRolePermissionSlugs) {
            return $this->roles()->pluck('slug');
        }

        return $this->roles;
    }

    public function getPermissions(): Collection
    {
        if ($this->gatherAllPermissions) {
            $perms = $this->allPermissions();
        } else {
            $perms = $this->permissions();
        }

        if ($this->onlyRolePermissionIds) {
            return $perms->pluck('id');
        } elseif ($this->onlyRolePermissionSlugs) {
            return $perms->pluck('slug');
        } elseif ($this->gatherAllPermissions) {
            return $perms;
        }

        return $this->permissions;
    }
}
