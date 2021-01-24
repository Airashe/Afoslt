<?php
/**
 * ExampleController.php - file that store an example of controller class.
 * PHP Version 7.3.
 *
 * @see       https://github.com/IIpocToTo4Ka/Afoslt - Afoslt GitHub repository.
 *
 * @author    Artem Khitsenko <eblludu247@gmail.com>
 * @copyright Copyright (c) 2020 IIpocTo_To4Ka.
 * @license   MIT License.
 * @package   Afoslt Core
 * @note      This code is distributed in the hope that it can help someone. 
 * The author does not guarantee that this code can somehow work and be useful 
 * or do anything at all.
 */
namespace Afoslt\Controllers\Examples;

use Afoslt\Core\Controller;
use Afoslt\Core\View;

/**
 * Example of controller.
 * 
 * @author Artem Khitsenko <eblludu247@gmail.com>
 */
final class ExampleController extends Controller
{
    /**
     * Example of controller's action.
     * 
     * @return void
     */
    public function ExampleAction (): View
    {
        return new View("example/index", ["Hello world!"]);
    }
}
