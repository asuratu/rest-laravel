<?php

namespace Modules\Admin\Transformers;

use Modules\Admin\Entities\AdminRole;
use League\Fractal\TransformerAbstract;

class AdminRoleTransformer extends TransformerAbstract
{
    public function transform(AdminRole $adminRole): array
    {
        return [
            'id' => $adminRole->id,
            'name' => $adminRole->name,
            'slug' => $adminRole->slug,
            'created_at' => (string) $adminRole->created_at,
            'updated_at' => (string) $adminRole->updated_at,
            'permissions' => $adminRole->permissions,
        ];
    }
}
