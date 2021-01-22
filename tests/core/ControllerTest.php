<?php

namespace Afoslt\Tests\Core;

use Afoslt\Controllers\Tests\TestController;
use Afoslt\Core\Application;
use Afoslt\Core\Controller;
use PHPUnit\Framework\TestCase;

/**
 * Tests for class `Controller`: **Core\Controller**.
 */
final class ControllerTest extends TestCase
{
    /**
     * Instance of an application.
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

        // Copy TestController file.     
        $tmpControllerDirectory = TEST_PATH_APPLICATION . "src" . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . "tests" . DIRECTORY_SEPARATOR;
        $applicationTestControllerTarget = $tmpControllerDirectory . "TestController.php";
        $applicationTestControllerOrigin = TEST_PATH_APPLICATION . "tests" . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "TestController.php";

        if(file_exists($applicationTestControllerTarget)) {
            unlink($applicationTestControllerTarget);
            rmdir($tmpControllerDirectory);
        }

        mkdir($tmpControllerDirectory);
        copy($applicationTestControllerOrigin, $applicationTestControllerTarget);

        ControllerTest::$appInstance = new Application;
    }

    public static function tearDownAfterClass(): void
    {
        if(!defined("TEST_PATH_APPLICATION"))
            define("TEST_PATH_APPLICATION", dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

        // Remove copy of TestController.
        $tmpControllerDirectory = TEST_PATH_APPLICATION . "src" . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . "tests" . DIRECTORY_SEPARATOR;
        $applicationTestControllerTarget = $tmpControllerDirectory . "TestController.php";

        unlink($applicationTestControllerTarget);
        rmdir($tmpControllerDirectory);

        ControllerTest::$appInstance = null;
    }

    /**
     * Test that checks results of Controller's static 
     * method `ClassName`.
     * 
     * @test 
     * 
     * @return void
     */
    public function testControllerClassNameWithKeywords (): void
    {
        new Application;

        $controllersShortNames = [
            'Users\\Settings', 
            '\\Users\\Settings', 
            '\\Users\\Settings\\', 
            '/Users/Settings', 
            'Users/Settings', 
            '/Users/Settings', 
            '/Users/Settings/', 
        ];

        fwrite(STDOUT, "\ntestControllerClassName:\n");

        fwrite(STDOUT, "\tAddKeywords: TRUE; Keyword: Controller; DEFAULT VALUES;\n");
        for($nameIndex = 0; $nameIndex < count($controllersShortNames); $nameIndex++)
        {
            fwrite(STDOUT, "\t\tController short name: " . $controllersShortNames[$nameIndex] . "\n");
            $controllerFullName = Controller::ClassName($controllersShortNames[$nameIndex]);
            fwrite(STDOUT, "\t\tController full name: " . $controllerFullName . "\n\n");
            $this->assertSame('Afoslt\\Controllers\\Users\\SettingsController', $controllerFullName, "Unexpected full name of a controller, method doesn't work correctly.");
        }
    }

    /**
     * Test for static method `Exists` of Controller class.
     * 
     * @test 
     * 
     * @return void
     */
    public function testControllerExists (): void
    {
        $this->assertTrue(Controller::Exists("Afoslt\Controllers\Tests\TestController"), "Can not find TestController with method `Exists`.");
        $this->assertTrue(Controller::Exists("Tests\TestController"), "Can not find TestController with method `Exists`.");
    }

    /**
     * Test for method `MethodExists` of Controller class.
     * 
     * @test 
     * 
     * @return void
     */
    public function testMethodExists (): void
    {
        $controller = new TestController;

        $methodsNames = [
            "TestAction", 
            "HelperAction", 
            "HiddenMethod", 
            "NEM", 
            "NEM2", 
        ];

        fwrite(STDOUT, "testMethodExists:\n");
        foreach($methodsNames as $methodName) {
            $methodExistsResult = $controller->MethodExists($methodName);

            fwrite(STDOUT, "\t" . $methodName . ": TestController MethodExists returns " . ($methodExistsResult ? "true" : "false") . "\n");

            $this->assertSame(method_exists($controller, $methodName), $methodExistsResult, $methodName . ": MethodExists of an instance returned different value than `method_exists`.");
        }
    }

    /**
     * Test for method `ActionExists` of Controller class.
     * 
     * @test 
     * 
     * @return void
     */
    public function testActionExists (): void
    {
        $actionsTests = [
            "TestAction" => true, 
            "Test" => true, 
            "HelperAction" => false, 
            "HiddenMethod" => false, 
            "NEA" => false, 
            "NEA2" => false, 
        ];

        $controller = new TestController;

        fwrite(STDOUT, "testActionExists:\n");
        foreach($actionsTests as $actionName => $expectedResult) {
            $actionExistsResult = $controller->ActionExists($actionName);

            fwrite(STDOUT, "\t" . $actionName . ": returns " . ($actionExistsResult ? "true" : "false") . "; expexted " . ($expectedResult ? "true" : "false") . "\n");

            $this->assertSame(  $expectedResult, $actionExistsResult, 
                                $actionName . ": expected " . ($expectedResult ? "true" : "false") . " but `ActionExists` returned " . ($actionExistsResult ? "true" : "false"));
        }
    }

    /**
     * Test for method `ActionName` of Controller class.
     * 
     * @test 
     * 
     * @return void
     */
    public function testActionName (): void
    {
        $actionsTests = [
            "TestAction" => "TestAction", 
            "Test" => "TestAction", 
            "ExampleAction" => "ExampleAction", 
            "Example" => "ExampleAction", 
        ];

        fwrite(STDOUT, "testActionName:\n");

        foreach($actionsTests as $actionName => $expectedResult) {
            $actualResult = Controller::ActionName($actionName);

            fwrite(STDOUT, "\t" . $actionName . ": result = " . $actualResult . "; expected = " . $expectedResult ."\n");

            $this->assertSame($expectedResult, $actualResult, $actionName . " expected " . $expectedResult . " but `ActionName` returned " . $actualResult);
        }
    }

    /**
     * Remove test controller.
     * 
     * @return void
     */
    private function RemoveTestController (): void
    {
        
    }
}
