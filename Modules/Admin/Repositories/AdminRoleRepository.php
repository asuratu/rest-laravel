<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Entities\AdminRole;
use ZhuiTech\BootLaravel\Repositories\BaseRepository;

class AdminRoleRepository extends BaseRepository
{
    public function model()
    {
        return AdminRole::class;
    }
}
