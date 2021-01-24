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
use ReflectionClass;

/**
 * Afoslt framework application class.
 * 
 * @author Artem Khitsenko <eblludu247@gmail.com>
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
    /**
     * Error code: application could not find requested 
     * controller class.
     * 
     * @var int
     */
    public const RESPONSE_ERROR_CONTROLLER_DOESNT_EXISTS = -2;
    /**
     * Error code: requested controller does not have requested 
     * action.
     * 
     * @var int
     */
    public const RESPONSE_ERROR_ACTION_DOEST_EXISTS = -3;
    // Fields ------------------------------------------

    /**
     * Associative array of properties, that defines how 
     * application will initialize.
     * 
     * By default value for array stored in **\config\manifest.php**.
     * 
     * Default keys:
     * + `routesDirectory` - Relative path to directory with routes files.
     * + `readGetPost` - Add keys and values from arrays $_GET and $_POST.
     * + `addKeywords` - Tells application to add keywords to controllers and actions name, when search for them.
     * + `controllersKeyword` - Keyword for Controllers.
     * + `actionsKeyword` - Keyword for Actions.
     * + `build` - Defines work mode that application will use.
     * + `startupSession` - Defines that session_start() will be called or not.
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
    /**
     * Associative array for storing values from 
     * configurations files.
     * 
     * @var array
     */
    private static $configuration = [];
    /**
     * Client's request to application.
     * 
     * @var string
     */
    private static $requestURI = "/";
    // Properties --------------------------------------

    /**
     * Associative array of properties, that defines how 
     * application will initialize.
     * 
     * By default value for array stored in **\config\manifest.php**.
     * 
     * Default keys:
     * + `routesDirectory` - Relative path to directory with routes files.
     * + `readGetPost` - Add keys and values from arrays $_GET and $_POST.
     * + `addKeywords` - Tells application to add keywords to controllers and actions name, when search for them.
     * + `controllersKeyword` - Keyword for Controllers.
     * + `actionsKeyword` - Keyword for Actions.
     * + `build` - Defines work mode that application will use.
     * + `startupSession` - Defines that session_start() will be called or not.
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
     * + `routesDirectory` - Relative path to directory with routes files.
     * + `readGetPost` - Add keys and values from arrays $_GET and $_POST.
     * + `addKeywords` - Tells application to add keywords to controllers and actions name, when search for them.
     * + `controllersKeyword` - Keyword for Controllers.
     * + `actionsKeyword` - Keyword for Actions.
     * + `build` - Defines work mode that application will use.
     * + `startupSession` - Defines that session_start() will be called or not.
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

    /**
     * Associative array for storing values from 
     * configurations files.
     * 
     * Add new values to application's configuration from 
     * another array.
     * 
     * @return void
     */
    protected static final function AddConfiguration (array $configValues): void
    {
        Application::$configuration = array_merge(Application::$configuration, $configValues);
    }

    /**
     * Associative array for storing values from 
     * configurations files.
     * 
     * @return array
     */
    public static final function GetConfiguration (): array
    {
        return Application::$configuration;
    }

    /**
     * Client's request to application.
     * 
     * @param string        $requestURI         New property's value.
     */
    private static function SetRequestURI (string $requestURI): void
    {
        Application::$requestURI = $requestURI;
    }

    /**
     * Client's request to application.
     * 
     * @return string
     */
    public static final function GetRequestURI (): string 
    {
        return Application::$requestURI;
    }

    // Methods -----------------------------------------

    /**
     * Constructor method of application class instance.
     * 
     * In first place constructor will define constants: 
     * + `PATH_APPLICATION` - path to application directory.
     * And load other constants defined in file Constants.php
     * 
     * Then constructor will call instance's methods in order:
     * + `LoadManifest()`
     * + `SetRouter()`
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
        /**
         * **Afoslt** constant.
         * 
         * Contains path to the directory of all applications folder relative to 
         * Application.php.
         * 
         * @var string
         */
        if(!defined("PATH_APPLICATION")) {
            $path = dirname(dirname(dirname( (new ReflectionClass(Application::class))->getFileName() )));
            if(defined("IN_UNIT_TESTS"))
                define("PATH_APPLICATION", $path . DIRECTORY_SEPARATOR);
            fwrite(STDOUT, "PATH_APPLICATION = " . PATH_APPLICATION . "\n");
        }

        require_once(PATH_APPLICATION . "src" . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "Constants.php");

        $this->LoadManifest();

        if(Application::GetManifest()['startupSession'] && session_status() === PHP_SESSION_NONE && headers_sent() === false)
            session_start();

        $this->SetRouter(new Router($this->LoadRoutes()));

        if(is_array($_SERVER) && array_key_exists('REQUEST_URI', $_SERVER))
            Application::SetRequestURI($_SERVER['REQUEST_URI']);
        else
            Application::SetRequestURI('/');

        $this->Main();
    }

    /**
     * Loading settings from manifest file by default 
     * path **\config\manifest.php**.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @return void
     */
    private function LoadManifest (): void
    {
        $manifestPath = PATH_APPLICATION . "config" . DIRECTORY_SEPARATOR . "manifest.php";
        if(!file_exists($manifestPath))
            $this->DropApplication(Application::RESPONSE_ERROR_NO_MANIFEST);

        $manifest = require $manifestPath;
        $manifest['routesDirectory'] = array_key_exists('routesDirectory', $manifest) ? $manifest['routesDirectory'] : "config" . DIRECTORY_SEPARATOR . "routes" . DIRECTORY_SEPARATOR;
        $manifest['readGetPost'] = array_key_exists('readGetPost', $manifest) ? $manifest['readGetPost'] : true;
        $manifest['addKeywords'] = array_key_exists('addKeywords', $manifest) ? $manifest['addKeywords'] : true;
        $manifest['controllersKeyword'] = array_key_exists('controllersKeyword', $manifest) ? $manifest['controllersKeyword'] : "Controller";
        $manifest['actionsKeyword'] = array_key_exists('actionsKeyword', $manifest) ? $manifest['actionsKeyword'] : "Action";
        $manifest['build'] = array_key_exists('build', $manifest) ? $manifest['build'] : BUILD_DEBUG;
        $manifest['startupSession'] = array_key_exists('startupSession', $manifest) ? $manifest['startupSession'] : true;
        Application::SetManifest($manifest);

        // Apply manifest values.
        error_reporting(E_ALL);
        switch(Application::$manifest['build']) {
            case BUILD_RELEASE:
                ini_set("display_errors", 0);
                break;
            default: 
                ini_set("display_errors", 1);
                break;
        }
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
    private function LoadRoutes (): array
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
                            $loadedRoutes = array_merge($loadedRoutes, $currentRoutes);
                    }
                }

            }
        }
        return $loadedRoutes;
    }

    /**
     * Loading configuration file from configuration folder.
     * 
     * Configuration files must have php extension and return 
     * an associative array.
     * 
     * Path to configuration file must be relative to configurations directory.
     * 
     * @param string    $relativeCfgPath        Relative path to configuration file.
     * 
     * @return bool
     * Returns **true** if configuration from file loaded successfully.
     */
    public final function LoadConfiguration (string $relativeCfgPath): bool
    {
        $relativeCfgPath = trim($relativeCfgPath, "\\/");
        
        $cfgPath = PATH_APPLICATION . "config" . DIRECTORY_SEPARATOR . $relativeCfgPath;
        if(file_exists($cfgPath)) {
            $configData = require $cfgPath;
            if(is_array($configData)) {
                Application::AddConfiguration($configData);
                return true;
            }
        }
        return false;
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
     * This is method where you can set up environment, 
     * before application will create controller and call action.
     * 
     * @return void
     */
    protected function OnReady (): void { }

    /**
     * Entry point of Application.
     * 
     * It will call method to read user's request and create 
     * controller.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * 
     * @return void
     */
    private function Main (): void
    {
        $this->GetRouter()->ReadRequest(Application::GetRequestURI());

        $controllerName = $this->router->GetControllerName();
        $actionName = Controller::ActionName($this->router->GetActionName());
        if(!empty($controllerName)) {
            $controllerFullName = Controller::ClassName($controllerName);
            if(Controller::Exists($controllerFullName)) {
                /**
                 * @var Controller
                 */
                $controller = new $controllerFullName;
                if($controller->ActionExists($actionName)) {
                    $controller->$actionName();
                    return;
                }

                $this->DropApplication(Application::RESPONSE_ERROR_ACTION_DOEST_EXISTS, "Controller(" . $controllerName . ") doesn't have action with name " . $actionName . ".");
            }
        }

        $this->DropApplication(Application::RESPONSE_ERROR_CONTROLLER_DOESNT_EXISTS, "Controller(" . $controllerName . ") doesn't exists");
    }
}