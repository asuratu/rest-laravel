<?php

namespace Modules\Admin\Utils;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Admin\Entities\AdminPermission;

class PermissionChecker
{
    /**
     * 允许特定权限通过.
     *
     * @param array|string|mixed $permission
     */
    public static function check($permission): bool
    {
        if (Admin::isAdministrator()) {
            return true;
        }

        if (is_array($permission)) {
            collect($permission)->each(function ($permission) {
                static::check($permission);
            });
        } elseif (Admin::user()->can($permission)) {
            return true;
        } else {
            static::error();
        }

        return true;
    }

    /**
     * 允许 $roles 中的任意一个角色访问.
     *
     * @param $roles
     */
    public static function allow($roles): bool
    {
        if (Admin::isAdministrator()) {
            return true;
        }

        if (!Admin::user()->inRoles($roles)) {
            static::error();
        }

        return true;
    }

    /**
     * 通行.
     *
     * @return bool
     */
    public static function free()
    {
        return true;
    }

    /**
     * 拒绝 roles 中的任意一个角色访问.
     *
     * @param $roles
     */
    public static function deny($roles): bool
    {
        if (Admin::isAdministrator()) {
            return true;
        }

        if (Admin::user()->inRoles($roles)) {
            static::error();
        }

        return true;
    }

    /**
     * 403 响应.
     */
    public static function error()
    {
        abort(403, '无权访问');
    }

    /**
     * 请求路径和方法的权限检测.
     *
     * @return bool
     */
    public static function shouldPassThrough(AdminPermission $permission, Request $request)
    {
        if (empty($permission->http_method) && empty($permission->http_path)) {
            return true;
        }

        $method = $permission->http_method;

        $matches = array_map(function ($path) use ($method) {
            if (Str::contains($path, ':')) {
                list($method, $path) = explode(':', $path);
                $method = explode(',', $method);
            }

            $path = 'admin' . $path;

            return compact('method', 'path');
        }, $permission->http_path);

        foreach ($matches as $match) {
            if (static::matchRequest($match, $request)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检测请求的方法和路径是否匹配特定值
     *
     * @return bool
     */
    protected static function matchRequest(array $match, Request $request)
    {
        if (!$request->is(trim($match['path'], '/'))) {
            return false;
        }

        $method = collect($match['method'])->filter()->map(function ($method) {
            return mb_strtoupper($method);
        });

        return $method->isEmpty() || $method->contains($request->method());
    }
}
