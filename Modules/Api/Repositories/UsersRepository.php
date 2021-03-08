<?php

namespace Modules\Api\Repositories;

use Modules\Api\Entities\User;
use ZhuiTech\BootLaravel\Repositories\BaseRepository;

class UsersRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }

}
