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
     * Examples:
     * + With keyword for controllers: *Controller*
     * 
     * **Input**: `Examples\Example`
     * 
     * **Output**: `examples\ExampleController`
     * 
     * + With keyword for controllers: *Test*
     * 
     * **Input**: `Index`
     * 
     * **Output**: `IndexTest`
     * 
     * @return string 
     * Returns full name of a class.
     */
    public static function ClassName (string $controllerShortClassName): string
    {
        return "";
    }
}
