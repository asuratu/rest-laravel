<?php

namespace Modules\Admin\Filters;

use Modules\Admin\Filters\Traits\RolePermissionFilter;

class AdminUserFilter extends Filter
{
    use RolePermissionFilter;

    protected array $simpleFilters = [
        'id',
        'username' => ['like', '%?%'],
        'name' => ['like', '%?%'],
    ];
    protected array $filters = [
        'role_name',
        'permission_name',
    ];
}
