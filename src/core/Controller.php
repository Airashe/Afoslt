<?php
/**
 * Controller.php - describes the Afoslt framework controller class.
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

use ReflectionMethod;

/**
 * Base class for all controllers in application.
 * 
 * @author Artem Khitsenko <eblludu247@gmail.com>
 */
abstract class Controller
{
    // Methods ---------------------------------------------

    /**
     * Gets a short version of class and 
     * returns full name based on current manifest settings.
     * 
     * Controller keyword and keyword using can be change in application's 
     * manifest.
     * 
     * @param null|string    $controllerShortClassName       Short class name (default from routes cfg files).
     * 
     * Examples:
     * + With keyword for controllers: *Controller*
     * 
     * **Input**: `Examples\Example`
     * 
     * **Output**: `Afoslt\Controllers\Examples\ExampleController`
     * 
     * + With keyword for controllers: *Test*
     * 
     * **Input**: `Index`
     * 
     * **Output**: `Afoslt\Controllers\IndexTest`
     * 
     * @return null|string 
     * Returns full name of a class.
     * 
     * For `null` will return `null`.
     */
    public static final function ClassName (?string $controllerShortClassName): string
    {
        if(!empty($controllerShortClassName)) {
            $controllerShortClassName = str_replace('/', '\\', $controllerShortClassName);
            $controllerShortClassName = trim($controllerShortClassName, '\\');
            
            $lastDirectorySeparatorIndex = strripos($controllerShortClassName, '\\');
            if(!is_bool($lastDirectorySeparatorIndex)) {
                $controllersNamespace = substr($controllerShortClassName, 0, $lastDirectorySeparatorIndex);
                $controllerName = substr($controllerShortClassName, $lastDirectorySeparatorIndex + 1);
            }
        
            $controllerFullName = "Afoslt\\Controllers\\" . $controllersNamespace . '\\' . $controllerName;
            if(Application::GetManifest()['addKeywords'] && preg_match("/" . Application::GetManifest()['controllersKeyword'] . '$/', $controllerFullName) === 0)
                $controllerFullName .= Application::GetManifest()['controllersKeyword'];
        }
        return $controllerFullName;
    }

    /**
     * Check if controller exists.
     * 
     * @param ?string   $controllerName     Name of controller.
     * 
     * @return bool
     * Returns **true** if controller exists.
     */
    public static final function Exists (?string $controllerName): bool
    {
        if(!empty($controllerName)) {
            $controllerName = str_replace('/', '\\', $controllerName);
            $controllerName = trim($controllerName, '\\');

            if(preg_match("/^Afoslt/", $controllerName) !== 1 && preg_match("/^src/", $controllerName) !== 1)
                $controllerName = Controller::ClassName($controllerName, Application::GetManifest()['addKeywords'], Application::GetManifest()['controllersKeyword']);

            $controllerRelativePath = str_replace("Afoslt", "src", $controllerName);
            $controllerAbsolutePath = PATH_APPLICATION . $controllerRelativePath . ".php";
            if(file_exists($controllerAbsolutePath))
                return true;
            else {
                $controllerNameDSlastpos = strripos($controllerRelativePath, "\\");
                $controllerClassName = substr($controllerRelativePath, $controllerNameDSlastpos + 1);
                $controllerNamespace = substr($controllerRelativePath, 0, $controllerNameDSlastpos + 1);
                $controllerNamespace = mb_strtolower($controllerNamespace);
                return file_exists(PATH_APPLICATION . $controllerNamespace . $controllerClassName . ".php");
            }
        }
        return false;
    }

    /**
     * Checks if controller have method with specific name.
     * 
     * @param null|string       Name of controller's method.
     * 
     * @return bool
     * Returns **true** if controller have method.
     * 
     * For `null` will return `false`.
     */
    public final function MethodExists (?string $methodName): bool
    {
        if(!empty($methodName))
            return method_exists($this, $methodName);
        return false;
    }

    /**
     * Checks if controller have action with specific name.
     * 
     * Difference between this method and `MethodExists` that this method 
     * will add keywords if manifest told so, also action could be only 
     * `public`.
     * 
     * @param null|string   $actionName     Action's name.
     * 
     * @return bool
     * Returns **true** if controller have action.
     * 
     * For `null` will always return `false`.
     */
    public final function ActionExists (?string $actionName): bool
    {
        if(!empty($actionName)) {
            $actionFullName = Controller::ActionName($actionName);

            if(method_exists($this, $actionFullName)) {
                $reflectionOfAction = new ReflectionMethod($this, $actionFullName);
                return $reflectionOfAction->isPublic();
            }
        }
        return false;
    }

    /**
     * Gets a short version of action of controller and 
     * returns full name based on current manifest settings.
     * 
     * Action's keyword and keyword using can be change in application's 
     * manifest.
     * 
     * @param null|string    $actionShortName       Short action's name.
     * 
     * Examples:
     * + With keyword for actions: *Action*
     * 
     * **Input**: `Example`
     * 
     * **Output**: `ExampleAction`
     * 
     * + With keyword for controllers: *Handler*
     * 
     * **Input**: `OnRegister`
     * 
     * **Output**: `OnRegisterHandler`
     * 
     * @return null|string 
     * Returns full name of an action.
     * 
     * For `null` will returns `null`.
     */
    public static final function ActionName (?string $actionShortName): ?string
    {
        if(!empty($actionShortName)) {
            if(Application::GetManifest()['addKeywords']) {

                $actionsKeyword = Application::GetManifest()['actionsKeyword'];
                $shortnameLength = strlen($actionShortName);
                $keywordLength = strlen($actionsKeyword);

                if($shortnameLength < $keywordLength || substr($actionShortName, $shortnameLength - $keywordLength, $keywordLength) !== $actionsKeyword)
                    $actionShortName .= $actionsKeyword;

            }
            return $actionShortName;
        }
        return null;
    }
}
