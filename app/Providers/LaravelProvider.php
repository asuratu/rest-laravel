<?php

namespace App\Providers;

use Log;
use ReflectionException;
use Illuminate\Support\Str;
use League\Fractal\Manager;
use Illuminate\Routing\Router;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Http\Kernel;
use ZhuiTech\BootLaravel\Middleware\Intranet;
use ZhuiTech\BootLaravel\Middleware\Language;
use ZhuiTech\BootLaravel\Middleware\PageCache;
use ZhuiTech\BootLaravel\Middleware\Signature;
use ZhuiTech\BootLaravel\Setting\SystemSetting;
use Illuminate\Contracts\Debug\ExceptionHandler;
use ZhuiTech\BootLaravel\Setting\CacheDecorator;
use ZhuiTech\BootLaravel\Setting\EloquentSetting;
use ZhuiTech\BootLaravel\Setting\SettingInterface;
use ZhuiTech\BootLaravel\Exceptions\AdvancedHandler;
use ZhuiTech\BootLaravel\Middleware\PrimaryThrottle;
use ZhuiTech\BootLaravel\Scheduling\ScheduleRegistry;
use ZhuiTech\BootLaravel\Middleware\SecondaryThrottle;
use ZhuiTech\BootLaravel\Transformers\ArraySerializer;
use ZhuiTech\BootLaravel\Providers\AbstractServiceProvider;

/**
 * 通用Laravel项目.
 */
class LaravelProvider extends AbstractServiceProvider
{
    protected $providers = [
        'Overtrue\LaravelUploader\UploadServiceProvider',
        'Overtrue\LaravelLang\TranslationServiceProvider',
    ];

    protected $commands = [];

    /**
     * Bootstrap the application services.
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function boot()
    {
        // 加载设置
        $this->loadSettings();

        // 加载数据库
        parent::loadMigrations();

        // 配置中间件
        // @var Router $router
        $router = $this->app['router'];
        $kernel = app(Kernel::class);
        $kernel->pushMiddleware(Language::class);
        $router->aliasMiddleware('intranet', Intranet::class);
        $router->aliasMiddleware('sign', Signature::class);
        $router->aliasMiddleware('cache', PageCache::class);
        $router->aliasMiddleware('throttle1', PrimaryThrottle::class);
        $router->aliasMiddleware('throttle2', SecondaryThrottle::class);

        // 加载路由
        parent::loadRoutes();

        // 配置授权
        $this->configAuth();

        parent::boot();
    }

    /**
     * Register the application services.
     *
     * @throws ReflectionException
     *
     * @return void
     */
    public function register()
    {
        // 强制HTTPS
        if (env('ADMIN_HTTPS')) {
            \URL::forceScheme('https');
        }

        // 中文
        Carbon::setLocale('zh');

        // 强制URL
        \URL::forceRootUrl(config('app.url'));

        $this->mergeConfig();

        // 命令行日志
        if (app()->runningInConsole()) {
            Log::setDefaultDriver('console');
        }

        // 异常处理
        $this->app->singleton(ExceptionHandler::class, AdvancedHandler::class);

        // 转化器
        $this->app->bind(Manager::class, function () {
            $manager = new Manager();
            $manager->setSerializer(new ArraySerializer());

            return $manager;
        });

        // 定时任务
        $this->app->singleton(ScheduleRegistry::class);

        // 视图
        $paths = config('view.paths');
        array_unshift($paths, $this->basePath('views'));
        config(['view.paths' => $paths]);

        // 系统设置
        $this->app->singleton(SettingInterface::class, function ($app) {
            $repository = new EloquentSetting(new SystemSetting());
            if (!config('boot-laravel.setting.cache')) {
                return $repository;
            }

            return new CacheDecorator($repository);
        });
        $this->app->alias(SettingInterface::class, 'system_setting');

        parent::register();
    }

    /**
     * 加载已经修改的配置.
     */
    private function loadSettings()
    {
        $settings = settings()->allToArray();
        foreach ($settings as $key => $value) {
            if (Str::contains($key, '.')) {
                config([$key => $value]);
            }
        }
    }

    /**
     * 配置授权机制.
     */
    private function configAuth()
    {
    }
}
