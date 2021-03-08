<?php

namespace Modules\Admin\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Admin\Repositories\AdminPermissionRepository;
use Modules\Admin\Resources\AdminPermissionResource;
use ZhuiTech\BootLaravel\Controllers\RestController;

class AdminPermissionController extends RestController
{
    protected $keywords = ['slug', 'name', 'http_path'];

    public function __construct(AdminPermissionRepository $repository)
    {
        parent::__construct($repository);
    }

    // 后台权限列表
    public function index(): JsonResponse
    {
        $data = request()->all();

        $result = $this->execIndex($data);

        return $this->success($result);
//        return $this->success(AdminPermissionResource::collection($result));
    }


//    public function store(AdminPermissionRequest $request, AdminPermission $model)
//    {
//        $inputs = $request->validated();
//        $res = $model->create($inputs);
//
//        return $this->created(AdminPermissionResource::make($res));
//    }
//
//    public function edit(AdminPermission $adminPermission)
//    {
//        return $this->okObject(AdminPermissionResource::make($adminPermission));
//    }
//
//    public function update(AdminPermissionRequest $request, AdminPermission $adminPermission)
//    {
//        $inputs = $request->validated();
//        $adminPermission->update($inputs);
//
//        return $this->created(AdminPermissionResource::make($adminPermission));
//    }
//
//    public function destroy(AdminPermission $adminPermission)
//    {
//        $adminPermission->delete();
//
//        return $this->noContent();
//    }
}
