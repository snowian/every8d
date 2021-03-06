<?php

namespace Recca0120\Every8d\Tests;

use Mockery as m;
use Recca0120\Every8d\Client;
use PHPUnit\Framework\TestCase;
use Recca0120\Every8d\Every8dServiceProvider;

class Every8dServiceProviderTest extends TestCase
{
    protected function setUp()
    {
        if (version_compare(PHP_VERSION, '5.6', '<') === true) {
            $this->markTestSkipped('PHP VERSION must bigger then 5.6');
        }
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testRegister()
    {
        $serviceProvider = new Every8dServiceProvider(
            $app = m::mock('Illuminate\Contracts\Foundation\Application, ArrayAccess')
        );

        $app->shouldReceive('singleton')->once()->with('Recca0120\Every8d\Client', m::on(function ($closure) use ($app) {
            $app->shouldReceive('offsetGet')->once()->with('config')->andReturn(
                $config = [
                    'services.every8d' => [
                        'user_id' => 'foo',
                        'password' => 'bar',
                    ],
                ]
            );

            return $closure($app) instanceof Client;
        }));

        $serviceProvider->register();
    }
}
