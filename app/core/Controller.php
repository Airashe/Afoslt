<?php
/**
 * Controller.php - описывает родительский класс для всех контроллеров
 * приложения.
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
namespace App\Core;

/**
 * Родительский класс для всех контроллеров приложения.
 * 
 * @author Артем Хиценко <eblludu247@gmail.com>
 */
abstract class Controller
{
    /**
     * Имя представления, которое назначено
     * этому контроллеру по умолчанию.
     */
    public $defaultViewName;
    /**
     * Текущее представление контроллера.
     */
    public $view;

    /**
     * Конструктор класса контроллера.
     * 
     * По умолчанию конструктор класса заполняет
     * поле `defaultViewName`, как имя представления
     * вызываемого по умолчанию.
     * 
     * Автоматическое заполнение составляет имя 
     * представления на основе переменных 
     * `Application::$controller` и `Application::$action`.
     * 
     * Например:
     * 
     * Имя контроллера: *ftbox\main*
     * 
     * Имя события: *index*
     * 
     * Результатом автоматического заполнения будет 
     * *ftbox\main\index*
     */
    public function __construct()
    {
        $this->defaultViewName = Application::$controller . DIRECTORY_SEPARATOR . Application::$action;
    }

    /**
     * Установка нового представления,
     * для этого контроллера.
     * 
     * @param string $view_name [опционально]
     * 
     * Имя нового представления.
     *
     * Если параметр пуст, то имя представления будет 
     * установлено как имя по умолчанию (поле 
     * `defaultViewName`).
     * 
     * @param string $layout_name [опционально]
     * Имя схемы.
     * 
     * По умолчанию имеет значение константы `LAYOUT_DEFAULT`.
     */
    public function setView(string $view_name = null, string $layout_name = LAYOUT_DEFAULT)
    {
        if(!$view_name)
            $view_name = $this->defaultViewName;
        $this->view = new View($view_name, $layout_name);
    }
    
    /**
     * Установка нового представления, 
     * для этого контроллера и его моментальное 
     * отображение.
     * 
     * Метод выполняет поочередный вызов метода `setView` 
     * контроллера и затем метода `render` 
     * созданного представления.
     * 
     * @param string $page_title [опционально]
     * Заголовок страницы.
     * 
     * @param array $arguments [опционально]
     * Аргументы с которыми будет отображен 
     * экземпляр представления.
     * 
     * @param string $view_name [опционально]
     * Имя нового представления.
     *
     * Если параметр пуст, то имя представления будет 
     * установлено как имя по умолчанию (поле 
     * `defaultViewName`).
     * 
     * @param string $layout_name [опционально]
     * Имя схемы.
     * 
     * По умолчанию имеет значение константы `LAYOUT_DEFAULT`.
     */
    public function setAndRender(string $page_title = null, $arguments = [], string $view_name = null, string $layout_name = LAYOUT_DEFAULT)
    {
        $this->setView($view_name);
        $this->view->render($page_title, $arguments);
    }
}
