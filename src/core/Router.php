<?php
/**
 * Router.php - describes the Afoslt framework router class.
 * PHP Version 7.3.
 *
 * @see       https://github.com/Airashe/Afoslt - Afoslt GitHub repository.
 *
 * @author    Artem Khitsenko <eblludu247@gmail.com>
 * @copyright Copyright (c) 2020 Airashe.
 * @license   MIT License.
 * @package   Afoslt Core
 * @note      This code is distributed in the hope that it can help someone. 
 * The author does not guarantee that this code can somehow work and be useful 
 * or do anything at all.
 */
namespace Afoslt\Core;

use Afoslt\Core\Application;

/**
 * Afoslt router. Router can read client's request and 
 * search matching route in application's routes array.
 * 
 * @author Artem Khitsenko <eblludu247@gmail.com>
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
    /**
     * Name of layout's file that will be used for view render.
     * 
     * @var string
     */
    private $layoutName = "";

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
     * @return null|string
     */
    public function GetControllerName (): ?string
    {
        return $this->controllerName;
    }

    /**
     * Name of controller that this router requesting
     * 
     * @param null|string    $controllerName     Controller's name.
     * 
     * @return void
     */
    private function SetControllerName (?string $controllerName): void
    {
        $this->controllerName = $controllerName;
    }

    /**
     * Name of controller's action that this router requesting
     * 
     * @return null|string
     */
    public function GetActionName (): ?string
    {
        return $this->actionName;
    }

    /**
     * Name of controller's action that this router requesting
     * 
     * @param null|string    $actionName         Action's name.
     * 
     * @return void
     */
    private function SetActionName (?string $actionName): void
    {
        $this->actionName = $actionName;
    }

    /**
     * Name of layout's file that will be used for view render.
     * 
     * @return null|string
     */
    public function GetLayoutName (): ?string
    {
        return $this->layoutName;
    }

    /**
     * Name of layout's file that will be used for view render.
     * 
     * @param null|string    $layoutName         New name of layout.
     * 
     * @return void
     */
    public function SetLayoutName (?string $layoutName): void
    {
        $this->layoutName = $layoutName;
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
    private function ReadRoutesArray (array $routes): void
    {
        foreach ($routes as $route => $routeParams) {
            $formattedRoute = trim(trim($route, '/'), '\\');
            $formattedRoute = preg_replace('#\{(\w+)\}#', '(?P<${1}>\w+)', $formattedRoute);
            $formattedRoute = '#^' . $formattedRoute . "$#";

            foreach($routeParams as $param => $value) {
                if(is_string($param)) {
                    switch($param) {
                        case 'controller':
                        case 'action':
                        case 'layout': 
                            if(!is_string($value) || empty($value))
                                unset($routeParams[$param]);
                            break;
                    }
                }
                else
                    unset($routeParams[$param]);
            }

            $this->AddRoutes($formattedRoute, $routeParams);
        }
    }

    /**
     * Read client's request to server.
     * 
     * @param null|string        $requestURI         Request URI.
     * 
     * @return bool
     * Returns `true` if requests matches with any of router's routes.
     */
    public final function ReadRequest (?string $requestURI): bool
    {
        if($requestURI != null) {
            $requestRoute = trim($requestURI, '/');
            $requestRoute = explode('?', $requestRoute)[0];
            
            if(Application::GetManifest()['readGetPost'])
                foreach ($_REQUEST as $requestArgKey => $requestArgValue)
                Application::GetArguments()[$requestArgKey] = $requestArgValue;
            
            foreach($this->GetRoutes() as $route => $routeParams) {
                if( preg_match($route, $requestRoute, $routeMatches) && is_array($routeParams) ) {
                    
                        // Controller
                        if(array_key_exists('controller', $routeParams))
                            $this->SetControllerName($routeParams['controller']);
                        else
                            $this->SetControllerName(null);
                        // Action
                        if(array_key_exists('action', $routeParams))
                            $this->SetActionName($routeParams['action']);
                        else
                            $this->SetActionName(null);
                        // Layout
                        if(array_key_exists('layout', $routeParams))
                            $this->SetLayoutName($routeParams['layout']);
                        elseif(array_key_exists('defaultLayout', Application::GetManifest()))
                            $this->SetLayoutName(Application::GetManifest()['defaultLayout']);
                        else
                            $this->SetLayoutName(null);
                        // ---------
                        return true;
                    
                        foreach($routeMatches as $matchKey => $matchValue) {
                            if(is_string($matchKey)) {
                                Application::AddArguments($matchKey, $matchValue);
                            }
                        }
                    }
            }
        }
        return false;
    }
}
