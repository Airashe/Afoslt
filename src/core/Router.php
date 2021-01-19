<?php
/**
 * Router.php - describes the Afoslt framework router class.
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

use Afoslt\Core\Application;

/**
 * Afoslt router. Router can read client's request and 
 * search matching route in application's routes array.
 */
final class Router 
{
    // Fields -------------------------------------------------------

    /**
     * Associative array of avaible routes for this 
     * instance of Router.
     * 
     * @var array
     */
    private $routes = [];
    /**
     * Name of controller that this router requesting.
     * 
     * @var string
     */
    private $controllerName = "";
    /**
     * Name of controller's action that this router requesting.
     * 
     * @var string
     */
    private $actionName = "";

    // Properties ---------------------------------------------------

    /**
     * Associative array of avaible routes for this 
     * instance of Router
     * 
     * @return array
     */
    public function GetRoutes (): array
    {
        return $this->routes;
    }

    /**
     * Associative array of avaible routes for this 
     * instance of Router
     * 
     * @param string    $route              Route.
     * @param array     $routeParams        Controller and its action that will be called when route is active.
     * 
     * @return void
     */
    private function AddRoutes (string $route, array $routeParams): void
    {
        $this->routes[$route] = $routeParams;
    }

    /**
     * Name of controller that this router requesting
     * 
     * @return string
     */
    public function GetControllerName (): string
    {
        return $this->controllerName;
    }

    /**
     * Name of controller that this router requesting
     * 
     * @param string    $controllerName     Controller's name.
     * 
     * @return void
     */
    private function SetControllerName (string $controllerName): void
    {
        $this->controllerName = $controllerName;
    }

    /**
     * Name of controller's action that this router requesting
     * 
     * @return string
     */
    public function GetActionName (): string
    {
        return $this->actionName;
    }

    /**
     * Name of controller's action that this router requesting
     * 
     * @param string    $actionName         Action's name.
     * 
     * @return void
     */
    private function SetActionName (string $actionName): void
    {
        $this->actionName = $actionName;
    }

    // Methods ------------------------------------------------------

    /**
     * Afoslt router. Router can read client's request and 
     * search matching route in application's routes array.
     * 
     * @param array    $routes         Associative array of routes.
     */
    public function __construct (array $routes)
    {
        $this->SetControllerName("");
        $this->SetActionName("");
        if(is_array($routes))
            $this->ReadRoutesArray($routes);
    }

    /**
     * Formatting all routes from parameters so 
     * that Router would work with them.
     * 
     * @param array     $routes         Associative array of routes.
     * 
     * @return void
     */
    private final function ReadRoutesArray (array $routes): void
    {
        foreach ($routes as $route => $routeParams) {
            $formattedRoute = trim(trim($route, '/'), '\\');
            $formattedRoute = preg_replace('#\{(\w+)\}#', '(?P<${1}>\w+)', $formattedRoute);
            $formattedRoute = '#^' . $formattedRoute . "$#";

            $this->AddRoutes($formattedRoute, $routeParams);
        }
    }

    /**
     * Read client's request to server.
     * 
     * @param ?string        $requestURI         Request URI.
     * 
     * @return void
     */
    public final function ReadRequest (?string $requesURI): void
    {
        if($requesURI != null) {
            $requestRoute = trim($requesURI, '/');
            $requestRoute =  explode('?', $requestRoute)[0];
            
            if(Application::GetManifest()['readGetPost'])
                foreach ($_REQUEST as $requestArgKey => $requestArgValue)
                Application::GetArguments()[$requestArgKey] = $requestArgValue;
            
            foreach($this->GetRoutes() as $route => $routeParams) {
                if( preg_match($route, $requestRoute, $routeMatches) && is_array($routeParams) &&
                    array_key_exists('controller', $routeParams) && array_key_exists('action', $routeParams) ) {
                    
                        $this->SetControllerName($routeParams['controller']);
                        $this->SetActionName($routeParams['action']);
                    
                        foreach($routeMatches as $matchKey => $matchValue) {
                            if(is_string($matchKey)) {
                                Application::AddArguments($matchKey, $matchValue);
                            }
                        }
                    }
            }
        }
    }
}
