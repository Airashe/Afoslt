<?php
/**
 * RouterTest.php - stores all tests for class Afoslt\Core\Router.
 * PHP Version 7.3.
 *
 * @see       https://github.com/IIpocToTo4Ka/Afoslt - Afoslt GitHub repository.
 *
 * @author    Artem Khitsenko <eblludu247@gmail.com>
 * @copyright Copyright (c) 2020 IIpocTo_To4Ka.
 * @license   MIT License.
 * @package   Afoslt Core UnitTests
 * @note      This code is distributed in the hope that it can help someone. 
 * The author does not guarantee that this code can somehow work and be useful 
 * or do anything at all.
 */
namespace Afoslt\Tests\Core;

use Afoslt\Core\Application;
use PHPUnit\Framework\TestCase;
use Afoslt\Core\Router;

/**
 * Tests for class `Router`: **Core\Router**.
 */
final class RouterTest extends TestCase
{
    /**
     * Instance of an application for tests.
     * @var Application
     */
    public static $appInstance;

    public static function setUpBeforeClass(): void
    {
        if(!defined("IN_UNIT_TESTS"))
            define("IN_UNIT_TESTS", true);

        if(!defined("TEST_PATH_APPLICATION"))
            define("TEST_PATH_APPLICATION", dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

        if(!defined("PATH_APPLICATION"))
            define("PATH_APPLICATION", dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

        ControllerTest::$appInstance = new Application;
    }

    public static function tearDownAfterClass(): void
    {
        if(!defined("TEST_PATH_APPLICATION"))
            define("TEST_PATH_APPLICATION", dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

        ControllerTest::$appInstance = null;
    }

    /**
     * Creating instance of router and give it associative 
     * arrays of routes.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @test
     * 
     * @return array
     */
    public function testFormattingRoutesArray (): array
    {
        $routes = [
            '/index' => ['controller' => 'index', 'action' => 'index'], 
            '/test/{testvar}' => ['controller' => 'test', 'action' => 'test'], 
        ];
        $router = new Router($routes);
        
        $this->assertTrue(is_array($router->GetRoutes()), "Routes is not an associative array.");
        $this->assertTrue($router->ReadRequest('/index'), "Could not find route, with request of exact route.");
        $this->assertTrue($router->ReadRequest('/index?abc=5'), "Could not find route, with request of exact route with GET request.");
        $this->assertFalse($router->ReadRequest('/test'), "Found route with arguments, when there is request without one.");
        $this->assertTrue($router->ReadRequest('/test/1'), "Could not find route with arguments, when there is one.");
        $this->assertTrue($router->ReadRequest('/test/1?abcd=6'), "Could not find route with arguments, when there is one and request contains GET.");

        return $routes;
    }

    /**
     * Creating instance of router and give it associative 
     * arrays of routes where routes does not contains slashes.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @test
     * 
     * @return void
     */
    public function testFormattingRoutesArrayNoSlashes (): void
    {
        $routes = [
            'index' => ['controller' => 'index', 'action' => 'index'], 
            'test/{testvar}' => ['controller' => 'test', 'action' => 'test'], 
        ];
        $router = new Router($routes);
        
        $this->assertTrue(is_array($router->GetRoutes()), "NS: Routes is not an associative array.");
        $this->assertTrue($router->ReadRequest('/index'), "NS: Could not find route, with request of exact route.");
        $this->assertTrue($router->ReadRequest('/index?abc=5'), "NS: Could not find route, with request of exact route with GET request.");
        $this->assertFalse($router->ReadRequest('/test'), "NS: Found route with arguments, when there is request without one.");
        $this->assertTrue($router->ReadRequest('/test/1'), "NS: Could not find route with arguments, when there is one.");
        $this->assertTrue($router->ReadRequest('/test/1?abcd=6'), "NS: Could not find route with arguments, when there is one and request contains GET.");
    }

    /**
     * Creating instance of router and give it associative 
     * arrays of routes where routes containes extra slashes.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @test
     * 
     * @return void
     */
    public function testFormattingRoutesArrayEndSlashes (): void
    {
        $routes = [
            '/index/' => ['controller' => 'index', 'action' => 'index'], 
            '/test/{testvar}/' => ['controller' => 'test', 'action' => 'test'], 
        ];
        $router = new Router($routes);
        
        $this->assertTrue(is_array($router->GetRoutes()), "ES: Routes is not an associative array.");
        $this->assertTrue($router->ReadRequest('/index'), "ES: Could not find route, with request of exact route.");
        $this->assertTrue($router->ReadRequest('/index?abc=5'), "ES: Could not find route, with request of exact route with GET request.");
        $this->assertFalse($router->ReadRequest('/test'), "ES: Found route with arguments, when there is request without one.");
        $this->assertTrue($router->ReadRequest('/test/1'), "ES: Could not find route with arguments, when there is one.");
        $this->assertTrue($router->ReadRequest('/test/1?abcd=6'), "ES: Could not find route with arguments, when there is one and request contains GET.");
    }

    /**
     * Creating instance of router and give it associative 
     * arrays of routes where routes containes back slashes.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @test
     * 
     * @return void
     */
    public function testFormattingRoutesArrayBackSlashes (): void
    {
        $routes = [
            '\\index\\' => ['controller' => 'index', 'action' => 'index'], 
            '\\test/{testvar}\\' => ['controller' => 'test', 'action' => 'test'], 
        ];
        $router = new Router($routes);
        
        $this->assertTrue(is_array($router->GetRoutes()), "BS: Routes is not an associative array.");
        $this->assertTrue($router->ReadRequest('/index'), "BS: Could not find route, with request of exact route.");
        $this->assertTrue($router->ReadRequest('/index?abc=5'), "BS: Could not find route, with request of exact route with GET request.");
        $this->assertFalse($router->ReadRequest('/test'), "BS: Found route with arguments, when there is request without one.");
        $this->assertTrue($router->ReadRequest('/test/1'), "BS: Could not find route with arguments, when there is one.");
        $this->assertTrue($router->ReadRequest('/test/1?abcd=6'), "BS: Could not find route with arguments, when there is one and request contains GET.");
    }

    /**
     * Checking that router is correctly reading route 
     * and get controller info from it.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @test
     * @depends testFormattingRoutesArray
     * 
     * @param array     $routes     Associative massive of routes.
     * 
     * @return void
     */
    public function testControllerRead (array $routes): void
    {
        $router = new Router($routes);

        $router->ReadRequest('/index');
        $this->assertSame($routes['/index']['controller'], $router->GetControllerName(), "Controller for test route has been read wrong.");
        $router->ReadRequest('/test/5');
        $this->assertSame($routes['/test/{testvar}']['controller'], $router->GetControllerName(), "Controller for test route has been read wrong.");
    }
}