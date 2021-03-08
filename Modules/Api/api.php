<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('cors')
    ->name('api.v1.')
    ->group(function () {
        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
                Route::prefix('users')->name('users')
                    ->group(function () {
                        // 登录
                        Route::put('/login', 'UsersController@login')
                            ->name('users.login');

                        // 注册
                        Route::post('/register', 'UsersController@register')
                            ->name('users.register');
                    });
            });

        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {

                // 登录后可以访问的接口
                Route::middleware(['jwt.auth'])
                    ->group(function () {
                        // 刷新token
                        Route::get('auth/refresh', 'AuthController@refresh')->name('auth.refresh');

                        // 退出登录
                        Route::get('auth/logout', 'AuthController@logout')->name('auth.logout');

                        // 获取当前用户信息
                        Route::get('users/me', 'UsersController@me')->name('users.me');

                    });
            });
    });
