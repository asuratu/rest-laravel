<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Entities\AdminRole;
use ZhuiTech\BootLaravel\Repositories\BaseRepository;

class AdminRoleRepository extends BaseRepository
{

    function model()
    {
        return AdminRole::class;
    }

}
