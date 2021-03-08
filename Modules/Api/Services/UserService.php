<?php
/**
 * Created by PhpStorm.
 * User: Tommy
 * Date: 2020/11/11
 * Time: 15:31
 */

namespace Modules\Api\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Modules\Api\Entities\User;
use Modules\Api\Repositories\UsersRepository;
use ZhuiTech\BootLaravel\Controllers\RestController;

class UserService extends RestController
{
    public function __construct(UsersRepository $repository)
    {
        parent::__construct($repository);
    }

    public function createUser($data)
    {
        return parent::execStore($data);
    }

    public function destroyToken()
    {
        if (request()->header('authorization')) {
            Auth::guard('api')->logout();
        }
    }

    /**
     * @Title: getToken
     * @param User | Authenticatable $user
     * @return array
     * @author Tommy
     * @date
     */
    public function getToken($user): array
    {
        return [
            'access_token' => Auth::guard('api')->fromUser($user),
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'type' => 'api'
        ];
    }


}
