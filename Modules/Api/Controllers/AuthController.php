<?php

namespace Modules\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use ZhuiTech\BootLaravel\Controllers\RestController;

class AuthController extends RestController
{
    public function refresh(): JsonResponse
    {
        $token = Auth::guard('api')->refresh(true);

        return $this->success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'type' => 'api'
        ]);
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return $this->success();
    }
}
