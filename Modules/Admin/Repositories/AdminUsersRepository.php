<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Entities\AdminUser;
use ZhuiTech\BootLaravel\Repositories\BaseRepository;


class AdminUsersRepository extends BaseRepository
{

    function model()
    {
        return AdminUser::class;
    }

    public function query($query)
    {
//        $filter = app(AdminUserFilter::class);

        $this->model = $this->model
//            ->filter($filter)
            ->with(['roles', 'permissions']);

        return parent::query($query);
    }

}
