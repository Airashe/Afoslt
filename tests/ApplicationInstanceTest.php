<?php
/**
 * ApplicationInstanceTest.php - stores all tests for concrete application class 
 * inherited from Core\Application.
 * PHP Version 7.3.
 *
 * @see       https://github.com/Airashe/Afoslt - Afoslt GitHub repository.
 *
 * @author    Artem Khitsenko <eblludu247@gmail.com>
 * @copyright Copyright (c) 2020 Airashe.
 * @license   MIT License.
 * @package   Afoslt Core UnitTests
 * @note      This code is distributed in the hope that it can help someone. 
 * The author does not guarantee that this code can somehow work and be useful 
 * or do anything at all.
 */
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
