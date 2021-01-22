<?php

namespace Afoslt\Tests;

use Afoslt\MyApplication;
use PHPUnit\Framework\TestCase;

final class ApplicationInstanceTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        if(!defined("IN_UNIT_TESTS"))
            define("IN_UNIT_TESTS", true);

        if(!defined("TEST_PATH_APPLICATION"))
            define("TEST_PATH_APPLICATION", dirname(__DIR__) . DIRECTORY_SEPARATOR);
    }

    /**
     * Test for any class that inherited from Application class
     * 
     * @test
     * 
     * @return void
     */
    public function testInstanceInitialization (): void
    {
        $applicationInstance = new MyApplication;
        $this->assertSame(TEST_PATH_APPLICATION, PATH_APPLICATION);
    }
}
