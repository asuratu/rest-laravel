<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Entities\AdminPermission;
use ZhuiTech\BootLaravel\Repositories\BaseRepository;


class AdminPermissionRepository extends BaseRepository
{

    function model()
    {
        return AdminPermission::class;
    }

}
