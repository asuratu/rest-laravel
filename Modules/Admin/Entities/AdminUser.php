<?php

namespace Modules\Admin\Entities;

use Modules\Admin\Traits\ModelHelpers;
use Modules\Admin\Utils\HasPermissions;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Modules\Admin\Traits\RolePermissionHelpers;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable implements JWTSubject
{
    use HasPermissions;
    use Notifiable;
    use ModelHelpers;
    use RolePermissionHelpers;

    public $timestamps = false;

    protected $table = 'admin_users';

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $fillable = ['username', 'password', 'name', 'avatar', 'created_at', 'updated_at'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsToMany(
            AdminRole::class,
            'admin_user_roles',
            'user_id',
            'role_id'
        );
    }

    public function permissions()
    {
        return $this->belongsToMany(
            AdminPermission::class,
            'admin_user_permissions',
            'user_id',
            'permission_id'
        );
    }

    /**
     * 从请求数据中添加用户.
     *
     * @param array $inputs
     * @param bool  $hashedPassword 传入的密码, 是否是没有哈希处理的明文密码
     *
     * @return AdminUser|\Illuminate\Database\Eloquent\Model
     */
    public static function createUser($inputs, $hashedPassword = false)
    {
        if (!$hashedPassword) {
            $inputs['password'] = bcrypt($inputs['password']);
        }

        $inputs['created_at'] = $inputs['updated_at'] = now();

        return static::create($inputs);
    }

    /**
     * 从请求数据中, 更新一条记录.
     *
     * @param array $inputs
     * @param bool  $hashedPassword 传入的密码, 是否是没有哈希处理的明文密码
     *
     * @return bool
     */
    public function updateUser($inputs, $hashedPassword = false)
    {
        // 更新时, 填了密码, 且没有经过哈希处理
        if (
            isset($inputs['password']) &&
            !$hashedPassword
        ) {
            $inputs['password'] = bcrypt($inputs['password']);
        }

        return $this->update($inputs);
    }
}
