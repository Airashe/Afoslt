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
     * 
     * @var int
     */
    public const RESPONSE_OK = 0;
    /**
     * Error code: application could not find manifest 
     * file.
     * 
     * @var int
     */
    public const RESPONSE_ERROR_NO_MANIFEST = -1;
    /**
     * Error code: application could not find routes 
     * matching with client's request
     * 
     * @var int
     */
    public const RESPONSE_ERROR_NO_MATCH_ROUTE = 404;
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
    /**
     * Associative array with arguments that application gets on startup 
     * from client request.
     * 
     * Arguments collect all values from GET, POST and Routes.
     * 
     * Priority of variables source:
     * 1. *Routes*
     * 2. *POST*
     * 3. *GET*
     * 
     * @var array
     */
    private static $arguments = [];
    /**
     * Name of controller class wich instance will be created.
     * 
     * @var string
     */
    private static $controllerName = "";
    /**
     * Name of controller's action that application will call.
     * 
     * @var string
     */
    private static $actionName = "";
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
        $this->ReadRequest();
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
        $dirsForScan = [PATH_APPLICATION . self::$manifest['routesDirectory']];
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
                            $formattedRoute = trim(trim($route, '/'), '\\');
                            $formattedRoute = preg_replace('#\{(\w+)\}#', '(?P<${1}>\w+)', $formattedRoute);
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
     * Read client's request to server.
     * 
     * @return void
     */
    private final function ReadRequest ()
    {
        $requestRoute = trim($_SERVER['REQUEST_URI'], '/');
        $requestRoute =  explode('?', $requestRoute)[0];

        if(self::$manifest['readGetPost'])
            foreach ($_REQUEST as $requestArgKey => $requestArgValue)
            self::$arguments[$requestArgKey] = $requestArgValue;

        foreach(self::$routes as $route => $routeParams) {
            if( preg_match($route, $requestRoute, $routeMatches) && is_array($routeParams) &&
                array_key_exists('controller', $routeParams) && array_key_exists('action', $routeParams) ) {

                    self::$controllerName = $routeParams['controller'];
                    self::$actionName = $routeParams['action'];

                    foreach($routeMatches as $matchKey => $matchValue) {
                        if(is_string($matchKey)) {
                            self::$arguments[$matchKey] = $routeMatches[$matchKey];
                        }
                    }

                    return;

                }
        }

        $this->DropApplication(self::RESPONSE_ERROR_NO_MATCH_ROUTE);
    }

    /**
     * Application errors handler.
     */
    public function DropApplication (int $code, string $message = "Internal error")
    {
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
        var_dump(self::$controllerName);
        var_dump(self::$actionName);
        var_dump(self::$arguments);
    }
}