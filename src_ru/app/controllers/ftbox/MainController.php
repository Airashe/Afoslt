<?php
/**
 * MainController.php - описывает контроллер главной страницы, предоставляемый 'из коробки'.
 * PHP Version 7.3.
 *
 * @see       https://github.com/IIpocToTo4Ka/Afoslt - Репозиторий Afoslt на GitHub.
 *
 * @author    Артем Хиценко <eblludu247@gmail.com>
 * @copyright Copyright (c) 2020 IIpocTo_To4Ka.
 * @license   MIT License.
 * @package   Afoslt
 * @note      Данный код распространяется с надеждой, что он может кому-то помочь. Автор 
 * не дает гарантию, что это код может хоть как-то работать и приность пользу или вообще 
 * что-либо делать.
 */
namespace App\Controllers\Ftbox;

use App\Core\Controller;
use App\Core\View;
use App\Models\Ftbox\Main;

/**
 * Класс содержащий события и логику главной страницы приложения.
 * @author Артем Хиценко <eblludu247@gmail.com>
 */
class MainController extends Controller
{
    /**
     * Метод вызываемый по умолчанию, при запросе главной страницы сайта.
     */
    public function indexAction()
    {
        $this->loadModel('ftbox\main');
        
        $arguments = [
            'message' => $this->loadedModels[0]->message,
            'loadedModels' => $this->loadedModels, 
        ];

        $this->setAndRender(null, $arguments);
    }
}
