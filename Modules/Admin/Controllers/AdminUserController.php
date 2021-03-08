<?php

namespace Modules\Admin\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Admin\Entities\AdminPermission;
use Modules\Admin\Entities\AdminRole;
use Modules\Admin\Entities\AdminUser;
use Modules\Admin\Repositories\AdminUsersRepository;
use Modules\Admin\Requests\AdminUserRequest;
use Modules\Admin\Transformers\AdminUserTransformer;
use ZhuiTech\BootLaravel\Controllers\RestController;

class AdminUserController extends RestController
{
    protected $keywords = ['username', 'name'];

    protected $transformer = 'Modules\Admin\Transformers\AdminUserTransformer';

    protected $listTransformer = 'Modules\Admin\Transformers\AdminUserListTransformer';

    public function __construct(AdminUsersRepository $repository)
    {
        parent::__construct($repository);
    }

    // 后台用户列表
    // 使用默认方法 index

    // 添加后台用户
    public function store(): JsonResponse
    {
        $request = app(AdminUserRequest::class);

        $inputs = $request->validated();

        $user = AdminUser::createUser($inputs);

        if (!empty($q = $request->post('roles', []))) {
            $user->roles()->attach($q);
        }
        if (!empty($q = $request->post('permissions', []))) {
            $user->permissions()->attach($q);
        }

        return $this->success($this->transformItem($user, new AdminUserTransformer()));
    }

    // 展示后台用户详情
    // 使用默认方法 show

    // 更新用户信息
    public function update($id): JsonResponse
    {
        $request = app(AdminUserRequest::class);

        $inputs = $request->validated();

        $adminUser = $this->findOrThrow($id);

        $adminUser->updateUser($inputs);

        if (isset($inputs['roles'])) {
            $adminUser->roles()->sync($inputs['roles']);
        }

        if (isset($inputs['permissions'])) {
            $adminUser->permissions()->sync($inputs['permissions']);
        }

        return $this->success($this->transformItem($adminUser, new AdminUserTransformer()));
    }


    // 删除用户
    // 使用父类接口

    // 编辑用户页面
    public function edit($id): JsonResponse
    {
        $formData = $this->formData();

        $adminUser = $this->findOrThrow($id);

        $adminUser->load(['roles', 'permissions']);

        $adminUser = $adminUser->onlyRolePermissionIds();

        return $this->success(
            $this->transformItem($adminUser, new AdminUserTransformer())
                ->setMeta($formData)
        );
    }

    // 创建新用户页面
    public function create(): JsonResponse
    {
        return $this->success($this->formData());
    }

    /**
     * 返回创建和编辑表单所需的选项数据.
     *
     * @return array
     */
    protected function formData(): array
    {
        $roles = AdminRole::query()
            ->orderByDesc('id')
            ->get();
        $permissions = AdminPermission::query()
            ->orderByDesc('id')
            ->get();

        return compact('roles', 'permissions');
    }
}
