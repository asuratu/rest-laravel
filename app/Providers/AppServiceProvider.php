<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use ZhuiTech\BootLaravel\Providers\AbstractServiceProvider;

class AppServiceProvider extends AbstractServiceProvider
{
    protected $providers = [
        LaravelProvider::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 中文
        Carbon::setLocale('zh');

        // 解决代理问题
        URL::forceRootUrl(config('app.url'));

        parent::boot();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!empty(env('CUSTOM_PROVIDER'))) {
            $this->providers[] = env('CUSTOM_PROVIDER');
        }

        parent::register();
    }
}
