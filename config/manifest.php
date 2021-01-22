<?php
/**
 * manifest.php - is a main configuration file for framework. Here you can set the 
 * way application will work.
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

return [
    /**
     * Name of your Afoslt application.
     */
    'name' => 'My Afoslt application', 
    /**
     * Directory where application sould search for routes cfg files.
     */
    'routesDirectory' => 'config\\routes\\', 
    /**
     * Copy $_GET & $_POST values to application's arguments.
     */
    'readGetPost' => true, 
    /**
     * Add keywords when using application classes and functions.
     */
    'addKeywords' => true, 
    /**
     * Keyword for controllers.
     */
    'controllersKeyword' => 'Controller', 
    /**
     * Keyword for actions.
     */
    'actionsKeyword' => 'Action', 
];
