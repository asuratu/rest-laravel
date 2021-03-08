<?php

namespace Modules\Admin\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Admin\Entities\AdminPermission;
use Modules\Admin\Repositories\AdminRoleRepository;
use Modules\Admin\Requests\AdminRoleRequest;
use Modules\Admin\Requests\AdminUserRequest;
use Modules\Admin\Transformers\AdminRoleTransformer;
use ZhuiTech\BootLaravel\Controllers\RestController;

class AdminRoleController extends RestController
{
    protected $keywords = ['slug', 'name'];

    protected $transformer = 'Modules\Admin\Transformers\AdminRoleTransformer';

    protected $listTransformer = 'Modules\Admin\Transformers\AdminRoleTransformer';

    public function __construct(AdminRoleRepository $repository)
    {
        parent::__construct($repository);
    }

    public function create(): JsonResponse
    {
        return $this->success($this->formData());
    }

    public function store(): JsonResponse
    {
        $request = app(AdminRoleRequest::class);

        $inputs = $request->validated();

        $role = parent::execStore($inputs);

        if (!empty($perms = $inputs['permissions'] ?? [])) {
            $role->permissions()->attach($perms);
        }

        return $this->success($this->transformItem($role, new AdminRoleTransformer()));
    }

    public function edit($id): JsonResponse
    {
        $formData = $this->formData();

        $adminRole = $this->findOrThrow($id);

        return $this->success(
            $this->transformItem($adminRole, new AdminRoleTransformer())
                ->setMeta($formData)
        );
    }

    public function update($id): JsonResponse
    {
        $request = app(AdminUserRequest::class);

        $inputs = $request->validated();

        $adminRole = $this->findOrThrow($id);

        parent::execUpdate($adminRole, $inputs);

        if (isset($inputs['permissions'])) {
            $adminRole->permissions()->sync($inputs['permissions']);
        }

        return $this->success($this->transformItem($adminRole, new AdminRoleTransformer()));
    }

    /**
     * 返回添加和编辑表单所需的选项数据.
     *
     * @return array
     */
    protected function formData(): array
    {
        $permissions = AdminPermission::query()
            ->orderByDesc('id')
            ->get();

        return compact('permissions');
    }
}
