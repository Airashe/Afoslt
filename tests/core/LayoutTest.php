<?php

/**
 * Layoutr.php - stores all tests for class Afoslt\Core\Layout.
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
namespace Afoslt\Tests\Core;

use Afoslt\Core\Layout;
use PHPUnit\Framework\TestCase;

final class LayoutTest extends TestCase
{
    public static function setUpBeforeClass (): void
    {
        if(!defined("IN_UNIT_TESTS"))
            define("IN_UNIT_TESTS", true);

        if(!defined("TEST_PATH_APPLICATION"))
            define("TEST_PATH_APPLICATION", dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

        if(!defined("PATH_APPLICATION"))
            define("PATH_APPLICATION", dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

        // Copy TestLayout file.     
        $tmpLayoutDirectory = TEST_PATH_APPLICATION . "src" . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . "tests" . DIRECTORY_SEPARATOR;
        $applicationTestLayoutTarget = $tmpLayoutDirectory . "testLayout.html";
        $applicationTestLayoutOrigin = TEST_PATH_APPLICATION . "tests" . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "testLayout.html";

        if(!file_exists($applicationTestLayoutTarget)) {
            mkdir($tmpLayoutDirectory);
            copy($applicationTestLayoutOrigin, $applicationTestLayoutTarget);
        }
    }

    public static function tearDownAfterClass (): void
    {
        if(!defined("TEST_PATH_APPLICATION"))
            define("TEST_PATH_APPLICATION", dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

        // Remove copy of TestController.
        $tmpLayoutDirectory = TEST_PATH_APPLICATION . "src" . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . "tests" . DIRECTORY_SEPARATOR;
        $applicationTestLayoutTarget = $tmpLayoutDirectory . "testLayout.html";

        if(file_exists($applicationTestLayoutTarget)) {
            unlink($applicationTestLayoutTarget);
            rmdir($tmpLayoutDirectory);
        }
    }

    /**
     * Test for method `Exists` of Layout class.
     * 
     * @test 
     * 
     * @return void
     */
    public function testLayoutExists (): void
    {
        $layoutSearchStrings = [
            "tests\\testLayout" => true, 
            "tests\\testLayout.html" => true, 
            "tests/testLayout" => true, 
            "tests/testLayout.html" => true, 
            "\\tests\\testLayout" => true, 
            "\\tests\\testLayout.html" => true, 
            "/tests/testLayout" => true, 
            "/tests/testLayout.html" => true, 
            "unexistingLayout" => false, 
            "unexistingLayout.html" => false, 
            "\\unexistingLayout" => false, 
            "\\unexistingLayout.html" => false, 
            "/unexistingLayout" => false, 
            "/unexistingLayout.html" => false, 
            null => false, 
        ];

        fwrite(STDOUT, "testLayoutExists:\n");
        foreach ($layoutSearchStrings as $layoutName => $expected)
        {
            $actual = Layout::Exists($layoutName);
            fwrite(STDOUT, "\t" . $layoutName . ": expected " . ($expected ? "true" : "false") . "; result " . ($actual ? "true" : "false") . "\n");
            $this->assertSame(  $expected, $actual, 
                                "Layout::Exists has been returned value different than expected. Layout name: " . $layoutName . "; Expected: " . ($expected ? "true" : "false") .
                                "Actual: " . ($actual ? "true" : "false"));
        }
    }
}
