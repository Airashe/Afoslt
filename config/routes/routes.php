<?php
/**
 * routes.php - is an example of routes configuration file for Afoslt.
 * PHP Version 7.3.
 * 
 * File returns array of routes.
 * 
 * Each route represented as: 
 * '%request%' => ['controller' => '%controller_name%', 'action' => '%action_name%', 'layout' => '%layout_name%'], 
 * 
 * where: 
 * %request% - request address.
 * %controller_name% - Name of the controller class, that will be created.
 * %action_name% - Name of the action of controller, that will be called.
 * %layout_name% - Name of layout file, that will be used for view render.
 *
 * @see       https://github.com/Airashe/Afoslt - Afoslt GitHub repository.
 *
 * @author    Artem Khitsenko <eblludu247@gmail.com>
 * @copyright Copyright (c) 2020 Airashe.
 * @license   MIT License.
 * @package   Afoslt
 * @note      This code is distributed in the hope that it can help someone. 
 * The author does not guarantee that this code can somehow work and be useful 
 * or do anything at all.
 */
return [
    '/' => ['controller' => 'Examples\Example',  
            'action' => 'Example'], 
];
