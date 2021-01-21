<?php

use Afoslt\Core\Application;
use Afoslt\Core\Controller;
use PHPUnit\Framework\TestCase;

/**
 * Tests for class `Controller`: **Core\Controller**.
 */
final class ControllerTest extends TestCase
{
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
     */
    public function testControllerExists (): void
    {
        new Application;

        // Copy TestController file.     
        $tmpControllerDirectory = PATH_APPLICATION . "src" . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . "tests" . DIRECTORY_SEPARATOR;
        $applicationTestControllerTarget = $tmpControllerDirectory . "TestController.php";
        $applicationTestControllerOrigin = PATH_APPLICATION . "tests" . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "TestController.php";

        mkdir($tmpControllerDirectory);
        copy($applicationTestControllerOrigin, $applicationTestControllerTarget);

        if(!defined("PATH_APPLICATION"))
            define("PATH_APPLICATION", dirname(dirname(__DIR__)));

        $this->assertTrue(Controller::Exists("Afoslt\Controllers\Tests\TestController"), "Can not find TestController with method `Exists`.");
        $this->assertTrue(Controller::Exists("Tests\TestController"), "Can not find TestController with method `Exists`.");
        
        // Remove copy of TestController.
        unlink($applicationTestControllerTarget);
        rmdir($tmpControllerDirectory);
    }
}
