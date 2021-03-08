<?php

namespace Modules\Api\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Api\Entities\User;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'username' => $user->username,
            'mobile' => $user->mobile,
        ];
    }
}
