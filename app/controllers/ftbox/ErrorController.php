<?php
/**
 * ErrorController.php - описывает контроллер странцы ошибок предоставляемый 'из коробки'. 
 * PHP Version 7.3.
 * 
 * В качестве базовых методов предоставляется: 
 * + `show404Action` - вызываемый в случаи отсутствия искомого контроллера, либо отсутствие 
 * события у запрашиваемого контроллера.
 * + `show403Action` - вызываемый в случаи когда доступ к запрашиваему маршруту запрещен.
 * + `applicationErrorAction` - вызываемый, когда нужно отобразить код ошибки приложения.
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

/**
 * Класс содержащий события и логику страницы с ошибками.
 * @author Артем Хиценко <eblludu247@gmail.com>
 */
class ErrorController extends Controller
{
    /** 
     * Метод вызываемый, чтобы отобразить страницу ошибки 404.
     */
    public function show404Action()
    {
        $this->setAndRender('Not Found');
    }

    /** 
     * Метод вызываемый, чтобы отобразить страницу ошибки 403.
     */
    public function show403Action()
    {
        $this->setAndRender('Forbidden');
    }

    /**
     * Метод вызываемый, чтобы отобразить страницу ошибки
     * приложения.
     */
    public function applicationErrorAction()
    {
        $this->setAndRender('Application error');
    }
}
