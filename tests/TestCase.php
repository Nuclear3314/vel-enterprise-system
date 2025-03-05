<?php

namespace VEL\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // 在這裡加入測試前的準備工作
    }

    protected function tearDown(): void
    {
        // 在這裡加入測試後的清理工作
        parent::tearDown();
    }
}