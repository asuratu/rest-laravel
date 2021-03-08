<?php

namespace Modules\Common\Utils\ApiEncrypt\AES;

use Closure;
use ZhuiTech\BootLaravel\Controllers\RestResponse;

class AesEncryptMiddleware
{
    use RestResponse;

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // 只对200进行加密
        if (200 != $response->getStatusCode()) {
            return $response;
        }

        $value = json_encode(json_decode($response->getContent())->data);

        return $this->success(encrypt($value, false));
    }
}
