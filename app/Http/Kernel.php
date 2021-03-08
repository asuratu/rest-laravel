<?php

namespace App\Http;

use Fruitcake\Cors\HandleCors;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Modules\Admin\Middleware\LogOperation;
use Modules\Api\Middleware\RecordRequestMessage;
use Modules\Common\Utils\ApiEncrypt\AES\AesDecryptMiddleware;
use Modules\Common\Utils\ApiEncrypt\AES\AesEncryptMiddleware;
use Modules\Common\Utils\Signature\Middleware\SignatureMiddleware;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Fruitcake\Cors\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

//        \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \App\Http\Middleware\TrimStrings::class,

        // 后台请求记录到数据库
//        LogOperation::class,
        // 前台请求记录到日志
//        RecordRequestMessage::class,
        // 参数加密
//        AesEncryptMiddleware::class

    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

//            \Modules\Admin\Http\Middleware\EncryptCookies::class,
//            \Illuminate\Session\Middleware\AuthenticateSession::class,
//            \Modules\Admin\Http\Middleware\VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'cors' => HandleCors::class,
        'api.signature' => SignatureMiddleware::class,
        'aes.decrypt' => AesDecryptMiddleware::class,
        'aes.encrypt' => AesEncryptMiddleware::class,
        // 后台请求记录到数据库
        'admin.log' => LogOperation::class,
        // 前台请求记录到日志
        'api.log' => RecordRequestMessage::class,
        // 区别前后台的token
        'jwt.auth.admin' => \Modules\Admin\Middleware\JwtAuthAdmin::class,

    ];

    protected $middlewarePriority = [
        // 请求进来，先解密再加密
        AesDecryptMiddleware::class,
        AesEncryptMiddleware::class,
    ];
}
