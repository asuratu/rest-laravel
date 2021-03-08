<?php

namespace Modules\Api\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Api\Repositories\UsersRepository;
use Modules\Api\Requests\User\LoginRequest;
use Modules\Api\Requests\User\RegisterRequest;
use Modules\Api\Services\UserService;
use Modules\Api\Transformers\UserTransformer;
use ZhuiTech\BootLaravel\Controllers\RestController;

class UserController extends RestController
{
    protected $transformer = 'Modules\Api\Transformers\UserTransformer';

    public function __construct(UsersRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * 用户注册.
     * @param RegisterRequest $request
     * @param UserService $userService
     * @return object
     */
    public function register(RegisterRequest $request, UserService $userService): JsonResponse
    {
        try {
            $user = $userService->createUser(['mobile' => $request->mobile, 'username' => Str::uuid(), 'password' => Hash::make($request->password)]);

            $token = $userService->getToken($user);

            return $this->success(
                $this->transformItem($user, new UserTransformer())
                    ->setMeta($token)
            );
        } catch (Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function login(LoginRequest $request, UserService $userService): JsonResponse
    {
        $credentials['mobile'] = $request->mobile;

        $credentials['password'] = $request->password;

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return $this->error(REST_LOGIN_FAIL);
        }

        $user = Auth::guard('api')->user();

        $tokenArr = $userService->getToken($user);

        return $this->success(
            $this->transformItem($user, new UserTransformer())
                ->setMeta($tokenArr)
        );
    }

}
