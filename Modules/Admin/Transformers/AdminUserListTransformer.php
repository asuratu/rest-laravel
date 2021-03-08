<?php

namespace Modules\Admin\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Admin\Entities\AdminUser;

class AdminUserListTransformer extends TransformerAbstract
{
    public function transform(AdminUser $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'roles' => $user->getRoles(),
            'permissions' => $user->getPermissions(),
            'created_at' => (string)$user->created_at,
            'updated_at' => (string)$user->updated_at
        ];
    }
}
