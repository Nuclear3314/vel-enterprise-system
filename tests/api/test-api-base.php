<?php
namespace VEL\Tests\API;

use WP_UnitTestCase;
use VEL\Includes\API\VEL_API_Base;

class Test_API_Base extends WP_UnitTestCase
{
    public function test_register_routes()
    {
        $api = $this->getMockForAbstractClass(VEL_API_Base::class);
        $api->register_routes();

        $routes = rest_get_server()->get_routes();
        $this->assertArrayHasKey('/vel/v1', $routes);
    }
}