<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class AddressControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testGetAddress()
    {
        $mock = \Mockery::mock(InspiringService::class);
        $mock->shouldReceive('inspire')->andReturn('名言');
        $inspiringController = new InspiringController($mock);
//        self::assertEquals(
//            '名言',
//            $inspiringController->inspire()
//        );
    }
}
