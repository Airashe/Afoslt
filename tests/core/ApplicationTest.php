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
     */
    public function testApplicationInitialization ()
    {
        $application = new Application();
        $this->assertSame(  dirname(dirname(__DIR__)) . '\\', PATH_APPLICATION, 
                            "Application directory not matching with application tests directory, /core and /tests must have same parent directory.");
        $this->assertTrue(count($application::GetManifest()) > 0, "Checking that application manifest has at least one parameter.");
        fwrite(STDERR, print_r($application::GetManifest(), TRUE));

        $routesDirectoryPath = realpath(PATH_APPLICATION . $application::GetManifest()['routes_directory']);
        $this->assertTrue(is_string($routesDirectoryPath), "Routes directory by path: " . $routesDirectoryPath . " not exists.");
        fwrite(STDERR, print_r($application::GetRoutes(), TRUE));
    }
}
