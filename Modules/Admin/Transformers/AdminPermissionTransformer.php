<?php

namespace Modules\Admin\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Admin\Entities\AdminPermission;

class AdminPermissionTransformer extends TransformerAbstract
{
    public function toArray(AdminPermission $adminPermission)
    {
        return [
            'id' => $adminPermission->id,
            'name' => $adminPermission->name,
            'slug' => $adminPermission->slug,
            'http_method' => $adminPermission->http_method,
            'http_path' => $adminPermission->http_path,
            'created_at' => (string)$adminPermission->created_at,
            'updated_at' => (string)$adminPermission->updated_at,
        ];
    }
}
