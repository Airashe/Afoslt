<?php
/**
 * Application.php - describes the Afoslt framework application class.
 * PHP Version 7.3.
 *
 * @see       https://github.com/IIpocToTo4Ka/Afoslt - Afoslt GitHub repository.
 *
 * @author    Artem Khitsenko <eblludu247@gmail.com>
 * @copyright Copyright (c) 2020 IIpocTo_To4Ka.
 * @license   MIT License.
 * @package   Afoslt
 * @note      This code is distributed in the hope that it can help someone. 
 * The author does not guarantee that this code can somehow work and be useful 
 * or do anything at all.
 */
namespace Afoslt\Core;

/**
 * Afoslt framework application class.
 */
class Application
{
    // Constants ---------------------------------------
    /**
     * Response code: everything is cool.
     * @var int
     */
    public const RESPONSE_OK = 0;
    /**
     * Error code: application could not find manifest 
     * file.
     * @var int
     */
    public const RESPONSE_ERROR_NO_MANIFEST = -1;
    // Fields ------------------------------------------

    /**
     * Array of all routes in the application.
     * 
     * By default, routes will be loaded from the directory 
     * specified in the manifest by key `routes_directory`.
     * 
     * @var array
     */
    private static $routes = [];
    /**
     * Associative array of properties, that defines how 
     * application will initialize.
     * 
     * By default value for array stored in **\config\manifest.php**.
     * 
     * Default keys:
     * + `name` - Application name.
     * + `routes_directory` - Relative path to directory with routes files.
     * 
     * @var array
     */
    private static $manifest = [];
    // Properties --------------------------------------

    /**
     * Array of all routes in the application.
     * 
     * By default, routes will be loaded from the directory 
     * specified in the manifest by key `routes_directory`.
     * 
     * @param array     $routes    New array of available routes.
     * 
     * @return void 
     */
    protected static final function SetRoutes (array $routes)
    {
        self::$routes = $routes;
    }

    /**
     * Array of all routes in the application.
     * 
     * By default, routes will be loaded from the directory 
     * specified in the manifest by key `routes_directory`.
     * 
     * @return array
     * Returns current array of application routes.
     */
    public static final function GetRoutes ()
    {
        return self::$routes;
    }

    /**
     * Associative array of properties, that defines how 
     * application will initialize.
     * 
     * By default value for array stored in **\config\manifest.php**.
     * 
     * Default keys:
     * + `name` - Application name.
     * + `routes_directory` - Relative path to directory with routes files.
     * 
     * @param array     $manifest   New array of application's parameters.
     */
    protected static final function SetManifest (array $manifest)
    {
        self::$manifest = $manifest;
    }

    /**
     * Associative array of properties, that defines how 
     * application will initialize.
     * 
     * By default value for array stored in **\config\manifest.php**.
     * 
     * Default keys:
     * + `name` - Application name.
     * + `routes_directory` - Relative path to directory with routes files.
     * 
     * @return array
     * Returns current array of application parameters.
     */
    public static final function GetManifest ()
    {
        return self::$manifest;
    }
    // Methods -----------------------------------------

    /**
     * Constructor method of application class instance.
     * 
     * In first place constructor will define constants: 
     * + `PATH_APPLICATION` - path to application directory.
     * 
     * Then constructor will call instance's methods in order:
     * + `LoadManifest()`
     * + `Main()`
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @return Application
     */
    public final function __construct ()
    {
        define("PATH_APPLICATION", dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

        $this->LoadManifest();
        $this->LoadRoutes();
        $this->Main();
    }

    /**
     * Loading settings from manifest file by default 
     * path **\config\manifest.php**.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @return int
     * Returns result code of method's work.
     * 
     * `0` if there was no errors.
     */
    private final function LoadManifest ()
    {
        $manifestPath = PATH_APPLICATION . "config" . DIRECTORY_SEPARATOR . "manifest.php";
        if(!file_exists($manifestPath))
            $this->DropApplication(self::RESPONSE_ERROR_NO_MANIFEST);
        self::SetManifest(require $manifestPath);
    }

    /**
     * Loading routes from all files in application routes directory.
     * 
     * This method will also scan subdirectories for routes files.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @return void
     */
    private final function LoadRoutes ()
    {
        $dirsForScan = [PATH_APPLICATION . self::$manifest['routes_directory']];
        $loadedRoutes = [];

        while(count($dirsForScan) > 0) {
            $currentDir = array_shift($dirsForScan);
            if(file_exists($currentDir) && is_dir($currentDir)) {
                $currentDirContent = array_diff(scandir($currentDir), ["..", "."]);

                while(count($currentDirContent) > 0) {
                    $currentElement = $currentDir . array_shift($currentDirContent); 
                    if(is_dir($currentElement))
                        array_push($dirsForScan, $currentElement . DIRECTORY_SEPARATOR);
                    else {
                        $currentRoutes = require $currentElement;
                        foreach($currentRoutes as $route => $routeParams) {
                            $formattedRoute = preg_replace('#\{(\w+)\}#', '(?P<${1}>\w+)', $route);
                            $formattedRoute = '#^' . $formattedRoute . "$#";
                            $loadedRoutes[$formattedRoute] = $routeParams;
                        }
                    }
                }

            }
        }
        self::SetRoutes($loadedRoutes);
    }

    /**
     * 
     */
    private final function ReadRequest ()
    {

    }

    /**
     * Application errors handler.
     */
    public function DropApplication (int $code, string $message = "Internal error")
    {
        echo $code . ': ' . $message;
        exit("Afoslt application has stopped working. Code: " . $code);
    }

    /**
     * This method executes before `Main`.
     * @return void
     */
    protected function OnReady ()
    {
        
    }

    /**
     * Entry point of Application.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     */
    private final function Main ()
    {
        
    }
}