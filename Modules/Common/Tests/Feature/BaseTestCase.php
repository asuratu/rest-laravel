<?php

namespace Modules\Common\Tests\Feature;

use Tests\TestCase;

/**
 * @coversNothing
 */
class BaseTestCase extends TestCase
{
    public $host;

    public $header;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->host = 'http://kit.test';
    }

    protected function setUp(): void
    {
        // 每个test方法之前都会调用一次这个方法
        parent::setUp();
        // 获取用户 token
        $this->header = $this->headers();
    }

    public function createApplication()
    {
        // @var \Illuminate\Foundation\Application $app
        $app = require __DIR__ . '/../../../../bootstrap/app.php';
        $app->loadEnvironmentFrom('.env.dev');

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        return $app;
    }

    protected function isLoginHeader(bool $flag = true)
    {
        if ($flag) {
            $token = $this->userLogin();

            return $this->headers($token);
        }

        return $this->header;
    }

    /**
     * @return string[]
     */
    protected function headers(string $token = '', array $addition = []): array
    {
        //添加版本号头部信息
        $headers = [
            'Accept' => 'application/prs.starter.v1.0+json',
        ];

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        if ($addition) {
            $headers = array_merge($headers, $addition);
        }

        return $headers;
    }

    private function userLogin()
    {
        // todo 需要先注册
        $url = $this->host . '/api/user/login';
        $response = $this->withHeaders($this->header)->post($url, [
            'username' => '115fd8bb-d64e-47b2-89fa-21640927aeb2',
            'password' => '102gzg9RBiLnOnwHx',
        ]);

        $userInfo = $response->getOriginalContent();

        return $userInfo['data']['token'];
    }
}
