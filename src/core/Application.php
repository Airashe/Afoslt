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
     * Path to the core directory of framework.
     * @var string
     */
    public const PATH_CORE = __DIR__;
    // Fields ------------------------------------------

    /**
     * List of all routes in the application.
     * 
     * By default, the list of routes is loaded from 
     * the *routes.php* configuration file.
     * 
     * @var array
     */
    private $routes;
    /**
     * List of framework properties, that defines how 
     * application will initialize.
     * 
     * @var array
     */
    private $manifest;
    // Properties --------------------------------------

    /**
     * List of all routes in the application.
     * 
     * By default, the list of routes is loaded from 
     * the *routes.php* configuration file.
     * 
     * @param array     $routes    New list of available routes.
     * 
     * @return void 
     */
    protected function SetRoutes (array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * List of framework properties, that defines how 
     * application will initialize.
     */
    protected function SetManifest (array $manifest)
    {
        $this->manifest = $manifest;
    }

    // Methods -----------------------------------------

    /**
     * Constructor method of application class instance.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @return Application
     */
    public final function __construct ()
    {
        $this->LoadManifest();
        $this->Main();
    }

    /**
     * Loading settings from manifest file.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     * @return int
     * Returns result code of method's work.
     * 
     * `0` if there was no errors.
     */
    protected function LoadManifest ()
    {
        define("PATH_APPLICATION", dirname(dirname(self::PATH_CORE)));
    }

    /**
     * Application errors handler.
     */
    public function DropApplication (int $code, string $message = "Internal error")
    {
        echo $code . ': ' . $message;
    }

    /**
     * Entry point of Application.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     */
    public final function Main ()
    {
        
    }
}