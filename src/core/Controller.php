<?php

namespace Afoslt\Core;

/**
 * Base class for all controllers in application.
 * 
 * @author Artem Khitsenko <eblludu247@gmail.com>
 */
abstract class Controller
{
    /**
     * Gets a short version of class (default from routes cfg files) and 
     * returns full name based on current manifest settings.
     * 
     * Controller keyword and keyword using can be change in application's 
     * manifest.
     * 
     * @param string    $controllerShortClassName       Short class name (default from routes cfg files).
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
     * @return string 
     * Returns full name of a class.
     */
    public static function ClassName (string $controllerShortClassName): string
    {
        $controllerShortClassName = str_replace('/', '\\', $controllerShortClassName);
        $controllerShortClassName = trim($controllerShortClassName, '\\');

        $lastDirectorySeparatorIndex = strripos($controllerShortClassName, '\\');
        if(!is_bool($lastDirectorySeparatorIndex)) {
            $controllersNamespace = substr($controllerShortClassName, 0, $lastDirectorySeparatorIndex);
            $controllerName = substr($controllerShortClassName, $lastDirectorySeparatorIndex + 1);
        }

        $controllerFullName = "Afoslt\\Controllers\\" . $controllersNamespace . '\\' . $controllerName;
        if(Application::GetManifest()['addKeywords'])
            $controllerFullName .= Application::GetManifest()['controllersKeyword'];

        return $controllerFullName;
    }

    /**
     * Check if controller exists.
     * 
     * @return bool
     * Returns **true** if controller exists.
     */
    public static function Exists (string $controllerName): bool
    {
        if(substr($controllerName, 0, 6) != "Afoslt")
            $controllerName = Controller::ClassName($controllerName, Application::GetManifest()['addKeywords'], Application::GetManifest()['controllersKeyword']);

        $controllerRelativePath = str_replace("Afoslt", "src", $controllerName);
        $controllerAbsolutePath = PATH_APPLICATION . $controllerRelativePath . ".php";

        if(!file_exists($controllerAbsolutePath))
            return true;
        else {
            $controllerNameDSlastpos = strripos($controllerRelativePath, "\\");
            $controllerClassName = substr($controllerRelativePath, $controllerNameDSlastpos + 1);
            $controllerNamespace = substr($controllerRelativePath, 0, $controllerNameDSlastpos + 1);
            $controllerNamespace = mb_strtolower($controllerNamespace);
            return file_exists(PATH_APPLICATION . $controllerNamespace . $controllerClassName . ".php");
        }
        
        return false;
    }
}
