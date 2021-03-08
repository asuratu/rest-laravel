<?php

namespace Modules\Admin\Controllers;

use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\JWTGuard;
use Modules\Admin\Utils\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Requests\AuthorizationRequest;
use Modules\Admin\Requests\AdminUserProfileRequest;
use Modules\Admin\Transformers\AdminUserTransformer;
use ZhuiTech\BootLaravel\Controllers\RestController;

class AdminController extends RestController
{
    public function adminLogin(AuthorizationRequest $request): JsonResponse
    {
        $credentials['username'] = $request->username;
        $credentials['password'] = $request->password;

        if (!$jwtToken = Auth::guard('admin')->attempt($credentials)) {
            return $this->error(46001);
        }

        $user = Auth::guard('admin')->user();

        $token = [
            'access_token' => $jwtToken,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('admin')->factory()->getTTL() * 60,
            'type' => 'admin',
        ];

        return $this->success(
            $this->transformItem($user, new AdminUserTransformer())
                ->setMeta($token)
        );
    }

    public function refresh(): JsonResponse
    {
        $token = Auth::guard('admin')->refresh();

        return $this->success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('admin')->factory()->getTTL() * 60,
            'type' => 'admin',
        ]);
    }

    public function me(): JsonResponse
    {
        return $this->success(
            $this->transformItem(Auth::guard('admin')->user(), new AdminUserTransformer())
        );
    }

    // 修改用户名、密码、头像
    public function profile(AdminUserProfileRequest $request): JsonResponse
    {
        $inputs = $request->validated();

        Admin::user()->updateUser($inputs);

        return $this->success(
            $this->transformItem(Admin::guard('admin')->user(), new AdminUserTransformer())
        );
    }

    // 当前用户及对应角色、权限（完整）
    public function currentUser(): JsonResponse
    {
        return $this->success(
            $this->transformItem(Auth::guard('admin')->user(), new AdminUserTransformer())
        );
    }

    // 当前用户及对应角色、权限（简略）
    public function user(): JsonResponse
    {
        $user = Admin::user();

        $user = $user->gatherAllPermissions();

        $user = $user->onlyRolePermissionSlugs();

        return $this->success(
            $this->transformItem($user, new AdminUserTransformer())
        );
    }

    /**
     * @return Guard|JWTGuard|JWT
     */
    protected function guard()
    {
        return Admin::guard();
    }
}
