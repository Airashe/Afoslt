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
     * @return void
     */
    public function testFormattingRoutesArray (): void
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
     * 
     * @return void
     */
    public function testControllerRead (): void
    {
        $routes = [
            '/index' => ['controller' => 'Index', 'action' => 'IndexA'], 
            '/test/{testvar}' => ['controller' => 'Test', 'action' => 'TestA'], 
            '/without/controller' => ['action' => 'wcA', 'layout' => 'wcL'], 
            '/without/action' => ['controller' => 'waC', 'layout' => 'waL'], 
            '/without/layout' => ['controller' => 'wlC', 'action' => 'wlA'], 
            'nodata' => [], 
            'differentTypes' => ['controller' => 1, 'action' => false, 'layout' => null], 
        ];
        $routesResults = [
            '/index' => [
                'requestURI' => '/index', 
                'methodResult' => true, 
                'controller' => 'Index', 
                'action' => 'IndexA', 
                'layout' => array_key_exists("defaultLayout", Application::GetManifest()) ? Application::GetManifest()['defaultLayout'] : null, 
            ], 
            '/test/{testvar}' => [
                'requestURI' => '/test/5', 
                'methodResult' => true, 
                'controller' => 'Test', 
                'action' => 'TestA', 
                'layout' => array_key_exists("defaultLayout", Application::GetManifest()) ? Application::GetManifest()['defaultLayout'] : null, 
            ], 
            '/without/controller' => [
                'requestURI' => '/without/controller', 
                'methodResult' => true, 
                'controller' => null, 
                'action' => 'wcA', 
                'layout' => 'wcL', 
            ], 
            '/without/action' => [
                'requestURI' => '/without/action', 
                'methodResult' => true, 
                'controller' => 'waC', 
                'action' => null, 
                'layout' => 'waL', 
            ], 
            '/without/layout' => [
                'requestURI' => '/without/layout', 
                'methodResult' => true, 
                'controller' => 'wlC', 
                'action' => 'wlA', 
                'layout' => array_key_exists("defaultLayout", Application::GetManifest()) ? Application::GetManifest()['defaultLayout'] : null, 
            ], 
            'nodata' => [
                'requestURI' => 'nodata', 
                'methodResult' => true, 
                'controller' => null, 
                'action' => null, 
                'layout' => array_key_exists("defaultLayout", Application::GetManifest()) ? Application::GetManifest()['defaultLayout'] : null, 
            ], 
            'differentTypes'=> [
                'requestURI' => 'differentTypes', 
                'methodResult' => true, 
                'controller' => null, 
                'action' => null, 
                'layout' => array_key_exists("defaultLayout", Application::GetManifest()) ? Application::GetManifest()['defaultLayout'] : null, 
            ], 
        ];
        $router = new Router($routes);

        fwrite(STDOUT, "testControllerRead\n");

        foreach ($routes as $route => $rp) {
            fwrite(STDOUT, "\t" . $route . ":\n");
            $routeResult = $routesResults[$route];
            $actualMethodResult = $router->ReadRequest($routeResult['requestURI']);

            fwrite(STDOUT,  "\t\t Request URI: " . $routeResult['requestURI'] . "\n\t\t\tExpected result: " . ($routeResult['methodResult'] ? "true" : "false") . 
                            "\n\t\t\tActual   result: " . ($actualMethodResult ? "true" : "false") . "\n\n");

            $this->assertSame(  $routeResult['methodResult'], $actualMethodResult, 
                                "\t" . $route . ": Request URI: " . $routeResult['requestURI'] . " ; expected request read result: " . ($routeResult['methodResult'] ? "true" : "false") . 
                                "; actual request read result: " . ($actualMethodResult ? "true" : "false") . "\n");
                        
            fwrite(STDOUT,  "\t\t\tExpected controller name: " . print_r($routeResult['controller'], true) . "\n");
            fwrite(STDOUT,  "\t\t\tActual   controller name: " . print_r($router->GetControllerName(), true) . "\n\n");

            $this->assertSame(  $routeResult['controller'], $router->GetControllerName(), 
                                "\t\t" . "Expected controller: " . $routeResult['controller'] . "; Actual controller: " . $router->GetControllerName() . "\n");

            fwrite(STDOUT,  "\t\t\tExpected action name: " . print_r($routeResult['action'], true) . "\n");
            fwrite(STDOUT,  "\t\t\tActual   action name: " . print_r($router->GetActionName(), true) . "\n\n");

            $this->assertSame(  $routeResult['action'], $router->GetActionName(), 
                                "\t\t" . "Expected action: " . $routeResult['action'] . "; Actual action: " . $router->GetActionName() . "\n");

            fwrite(STDOUT,  "\t\t\tExpected action name: " . print_r($routeResult['layout'], true) . "\n");
            fwrite(STDOUT,  "\t\t\tActual   action name: " . print_r($router->GetLayoutName(), true) . "\n\n");

            $this->assertSame(  $routeResult['layout'], $router->GetLayoutName(), 
                                "\t\t" . "Expected layout: " . $routeResult['layout'] . "; Actual layout: " . $router->GetLayoutName() . "\n");

        }
    }
}