<?php
/**
 * View.php - описывает родительский класс для всех представлений
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
 * Родительский класс для всех представлений приложения.
 * 
 * @author Артем Хиценко <eblludu247@gmail.com>
 */
class View
{
    /**
     * Имя слоя представления.
     * @var string
     */
    public $name;

    /**
     * Имя схемы, которая будет отрисована
     * до отображения слоя представления.
     * @var string
     */
    public $layoutName;

    /**
     * Путь к файлу предствления.
     * @var string
     */
    protected $viewFile;

    /**
     * Путь к файлу схемы.
     * @var string
     */
    protected $layoutFile;

    /**
     * Конструктор нового экземпляра представления 
     * приложения.
     * 
     * @param string $name Имя представления.
     * @param string $layout_name [опционально]
     * Имя схемы.
     * 
     * По умолчанию имеет значение константы `LAYOUT_DEFAULT`.
     * 
     * @exceptions
     * + *Exception* - Не удалось создать представление, 
     * так как файл представления не найден.
     * + *Exception* - Не удалось создать предствление, 
     * так как файл схемы не найден.
     */
    public function __construct(string $name, string $layout_name = LAYOUT_DEFAULT)
    {
        $this->name = $name;
        $this->layoutName = $layout_name;

        $this->viewFile = $_SERVER['DOCUMENT_ROOT'] . DIR_VIEWS . $this->name . '.php';
        $this->layoutFile = $_SERVER['DOCUMENT_ROOT'] . DIR_LAYOUTS . $this->layoutName . '.php';

        if(!file_exists($this->viewFile))
            throw new \Exception("Can not create view (" . $this->name . "). View file (" . $this->viewFile . ") not exists.", 4);
        if(!file_exists($this->layoutFile))
            throw new \Exception("Can not create view (" . $this->name . "). Layout file (" . $this->layoutFile . ") not exists.", 5);    
    }
    /**
     * Отрисовка представления.
     * 
     * @param string $page_title [опционально]
     * Заголовок страницы.
     * @param array $arguments [опционально]
     * Аргументы с которыми будет отображен 
     * экземпляр представления.
     */
    public function render(string $page_title = null, $arguments = [])
    {
        if(!$page_title)
            $page_title = Application::$manifest['application_name'];

        extract($arguments);
        ob_start();
        require $this->viewFile;
        $page_content = ob_get_clean();
        require $this->layoutFile;
    }
}
