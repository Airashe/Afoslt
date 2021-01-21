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

use Afoslt\Core\Router;

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
     * Instance of class router for this application.
     * 
     * @var Router
     */
    private $router;
    // Properties --------------------------------------

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
     * 
     * @return void
     */
    protected static final function SetManifest (array $manifest): void
    {
        Application::$manifest = $manifest;
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
    public static final function GetManifest (): array
    {
        return Application::$manifest;
    }

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
     * @return array
     */
    public static final function GetArguments (): array
    {
        return Application::$arguments;
    }

    /**
     * Add new argument to application's arguments array.
     * 
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
     * @param string    $name        Argument's name.
     * @param mixed     $value       Argument's value.
     * 
     * @return void
     */
    public static final function AddArguments (string $name, mixed $value): void
    {
        Application::$arguments[$name] = $value;
    }

    /**
     * Instance of class router for this application.
     * 
     * @param Router    $router     Instance of class router.
     * 
     * @return void
     */
    protected final function SetRouter (Router $router): void
    {
        $this->router = $router;
    }

    /**
     * Instance of class router for this application.
     * 
     * @return Router
     */
    protected final function GetRouter (): Router
    {
        return $this->router;
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
     * + `OnReady()`
     * + `Main()`
     * 
     * After loading application manifest and before calling 
     * method `OnReady()` application will create instance of 
     * `Router` for reading avaible routes and client's request.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @return Application
     */
    public final function __construct ()
    {
        define("PATH_APPLICATION", dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR);

        $this->LoadManifest();

        $this->SetRouter(new Router($this->LoadRoutes()));

        $reuqestURI = is_array($_SERVER) && array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '';
        $this->GetRouter()->ReadRequest($reuqestURI);

        $this->Main();
    }

    /**
     * Loading settings from manifest file by default 
     * path **\config\manifest.php**.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @return void
     */
    private final function LoadManifest (): void
    {
        $manifestPath = PATH_APPLICATION . "config" . DIRECTORY_SEPARATOR . "manifest.php";
        if(!file_exists($manifestPath))
            $this->DropApplication(Application::RESPONSE_ERROR_NO_MANIFEST);

        $manifest = require $manifestPath;
        $manifest['name'] = array_key_exists('name', $manifest) ? $manifest['name'] : "My Afoslt application";
        $manifest['routesDirectory'] = array_key_exists('routesDirectory', $manifest) ? $manifest['routesDirectory'] : "config" . DIRECTORY_SEPARATOR . "routes" . DIRECTORY_SEPARATOR;
        $manifest['readGetPost'] = array_key_exists('readGetPost', $manifest) ? $manifest['readGetPost'] : true;
        Application::SetManifest($manifest);
    }

    /**
     * Loading routes from all files in application routes directory.
     * 
     * This method will also scan subdirectories for routes files.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * 
     * @return array 
     * Returns merge associative array of all associative 
     * arrays in routes directory.
     */
    private final function LoadRoutes (): array
    {
        $dirsForScan = [PATH_APPLICATION . Application::GetManifest()['routesDirectory']];
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
                        if(is_array($currentRoutes))
                            array_merge($loadedRoutes, $currentRoutes);
                    }
                }

            }
        }
        return $loadedRoutes;
    }

    /**
     * Application errors handler.
     * 
     * @return void
     */
    protected function DropApplication (int $code, string $message = "Internal error"): void
    {
        exit("Afoslt application has stopped working. Code: " . $code . " " . $message);
    }

    /**
     * This method executes before `Main`.
     * 
     * @return void
     */
    protected function OnReady (): void
    {
        
    }

    /**
     * Entry point of Application.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * 
     * @return void
     */
    private final function Main (): void
    {
        
    }
}