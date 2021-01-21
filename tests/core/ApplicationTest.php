<?php
/**
 * ApplicationTest.php - Набор тестирующих юнитов для класса Core\Application.
 * PHP Version 7.3.
 *
 * @see       https://github.com/IIpocToTo4Ka/Afoslt - Репозиторий Afoslt на GitHub.
 *
 * @author    Артем Хиценко <eblludu247@gmail.com>
 * @copyright Copyright (c) 2020 IIpocTo_To4Ka.
 * @license   MIT License.
 * @package   Afoslt
 * @note      Данный код распространяется с надеждой, что он может кому-то помочь. Автор 
 * не дает гарантию, что это код может хоть как-то работать и приность пользу или вообще 
 * что-либо делать.
 */
namespace Afoslt\Tests\Core;

use PHPUnit\Framework\TestCase;
use Afoslt\Core\Application;

use function PHPUnit\Framework\directoryExists;

/**
 * Tests for class `Application`: **Core\Application**.
 */
final class ApplicationTest extends TestCase
{
    /**
     * Creating instance of application and checking 
     * that all preferences loading correctly.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @test
     * 
     * @return void
     */
    public function testApplicationInitialization (): Application
    {
        /**
         **Afoslt test** constant.
         * 
         * Contains path to the directory of all applications folder relative to 
         * ApplicationTest.php.
         * 
         * @var string
         */
        define("TESTS_PATH_APPLICATION", dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

        $application = new Application();

        // Checking that PATH_APPLICATION defines correctly.
        $this->assertSame(  TESTS_PATH_APPLICATION, PATH_APPLICATION, 
                            "Application directory not matching with application tests directory, /core and /tests must have same parent directory.");
        // Checking that application could load manifest.
        $manifestPath = PATH_APPLICATION . "config" . DIRECTORY_SEPARATOR . "manifest.php";
        $this->assertTrue(  file_exists($manifestPath), 
                            "Tests could not find manifest file.");
        fwrite(STDOUT, "Manifest path: " . $manifestPath . "\n");

        // Checking that manifest have at least one value.
        $this->assertTrue(count($application::GetManifest()) > 0, "Checking that application manifest has at least one parameter.");
        fwrite(STDERR, "Manifest data: " . print_r($application::GetManifest(), TRUE) . "\n");

        // Checking that routes directory exists.
        $routesDirectoryPath = realpath(PATH_APPLICATION . $application::GetManifest()['routesDirectory']);
        $this->assertTrue(is_string($routesDirectoryPath), "Routes directory by path: " . $routesDirectoryPath . " not exists.");

        return $application;
    }

    /**
     * Testing that application could load additional configurations files.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @test
     * @depends testApplicationInitialization
     */
    public function testLoadingAdditionalConfigurations (Application $application): void
    {
        /**
         * Creating test configuration file.
         */
        $testConfigDirectory = TESTS_PATH_APPLICATION . "config" . DIRECTORY_SEPARATOR . "tests" . DIRECTORY_SEPARATOR;
        $testConfigFile = $testConfigDirectory . "testcfg.php";

        mkdir($testConfigDirectory);
        file_put_contents($testConfigFile, "<?php return ['testKey' => 'testValue'];");

        // Checking that application correctly loading additional cfg files.
        $application->LoadConfiguration('tests' . DIRECTORY_SEPARATOR . 'testcfg.php');
        $this->assertTrue(array_key_exists('testKey', Application::GetConfiguration()), "Application did not add test configuration file to associative array.");
        $this->assertSame(Application::GetConfiguration()['testKey'], 'testValue', "Value from test configuration file is not valid.");
        
        // Removing test configuration file.
        unlink($testConfigFile);
        rmdir($testConfigDirectory);
    }
}
