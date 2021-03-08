<?php

namespace Modules\Admin\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Admin\Requests\AdminPermissionRequest;
use ZhuiTech\BootLaravel\Controllers\RestController;
use Modules\Admin\Repositories\AdminPermissionRepository;

class AdminPermissionController extends RestController
{
    protected $keywords = ['slug', 'name', 'http_path'];

    protected $transformer = 'Modules\Admin\Transformers\AdminPermissionTransformer';

    protected $listTransformer = 'Modules\Admin\Transformers\AdminPermissionTransformer';

    public function __construct(AdminPermissionRepository $repository)
    {
        parent::__construct($repository);
    }

    // 后台权限列表
    // 使用默认方法 index

    public function store(): JsonResponse
    {
        $request = app(AdminPermissionRequest::class);

        $request->validated();

        return parent::store();
    }

    public function update($id): JsonResponse
    {
        $request = app(AdminPermissionRequest::class);

        $request->validated();

        return parent::update($id);
    }
}
