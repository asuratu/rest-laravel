<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('cors')
    ->name('admin.v1.')
    ->group(function () {
        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
                Route::prefix('authorizations')->name('authorizations')
                    ->group(function () {
                        // 登录
                        Route::post('/', 'AdminController@adminLogin')
                            ->name('login');
                    });
            });

        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {

                // 登录后可以访问的接口
                Route::middleware(['jwt.auth.admin', 'jwt.auth'])
                    ->group(function () {
                        // 刷新token
                        Route::put('current', 'AdminController@refresh')
                            ->name('refresh');

                        // 获取当前用户信息
                        Route::get('auth/me', 'AdminController@me')->name('auth.me');

                        // 获取当前用户信息及对应角色、权限（完整）
                        Route::get('auth/currentUser', 'AdminController@currentUser')->name('auth.current');

                        // 获取当前用户信息及对应角色、权限（简略）
                        Route::get('auth/user', 'AdminController@user')->name('auth.user');

                        // 修改用户信息（用户名、密码、头像）
                        Route::put('auth/profile', 'AdminController@profile')->name('auth.profile');

                        // 后台用户
                        Route::resource('admins', 'AdminUserController');

                        // 权限
                        Route::resource('permissions', 'AdminPermissionController');

                        // 角色
                        Route::resource('roles', 'AdminRoleController');
                    });
            });
    });
