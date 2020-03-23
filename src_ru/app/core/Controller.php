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

use Exception;

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
     * @var string
     */
    public $defaultViewName;
    /**
     * Текущее представление контроллера.
     * @var View
     */
    public $view;
    /**
     * Список загруженных, во время работы, моделей.
     * @var array
     */
    public $loadedModels;

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
        $this->loadedModels = array();
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

    /**
     * Загрузка класса модели и создание его 
     * экземпляра.
     * 
     * Созданный экземпляр будет добавлен в поле-массив 
     * `loadedModels` контроллера.
     * 
     * @param string $model_name Имя модели.
     * @param bool $generate_full_names [опционально]
     * 
     * Нужно ли генерировать полные имена, при помощи метода и 
     * `Model::className`, прежде чем осуществлять проверку.
     * 
     * @exceptions Исключения:
     * + Exception - Запрашиваемая модель не найдена.
     */
    public function loadModel(string $model_name, bool $generate_full_names = true)
    {
        if($generate_full_names)
            $model_name = Model::className($model_name);
        if(Model::exists($model_name, false)) {
            $model = new $model_name();
            array_push($this->loadedModels, $model);
        } else
            throw new Exception("Can not find model class (" . $model_name . ").", 43);
    }

    /**
     * Получение полного имени класса контроллера.
     * 
     * Получает сокращенное имя контроллера *( Например: main )* 
     * и возвращает его полное имя *( Например: \app\controllers\MainController )*.
     * 
     * Метод использует константы `DIR_CONTROLLERS`, `KEYWORD_CONTROLLER` и параметр `add_keyword_to_objects`
     * **манифеста**, чтобы сгенерировать полное имя контроллера.
     * 
     * @param string $controller_name Сокращенное имя контроллера.
     * 
     * @return string Полное имя контроллера.
     */
    public static function className(string $controller_name)
    {
        $controller_subdirectory = '';
        if($lastSDPos = strrpos($controller_name, '\\')) {
            $controller_subdirectory = substr($controller_name, 0, $lastSDPos) . DIRECTORY_SEPARATOR;
            $controller_name = substr($controller_name, $lastSDPos + 1);
        }
        $contoller_path = DIR_CONTROLLERS . $controller_subdirectory . ucfirst($controller_name);
        $contoller_path .= Application::$manifest['add_keyword_to_objects'] ? KEYWORD_CONTROLLER : '';
        return $contoller_path;
    }

    /** 
     * Проверяет существование контроллера.
     * 
     * @param string $controller_name Имя контроллера.
     * @param bool $generate_full_names [опционально]
     * 
     * Нужно ли генерировать полные имена, при помощи метода и 
     * `Cotroller::ClassName`, прежде чем осуществлять проверку.
     * 
     * @return bool Возвращает **true** - если контроллер существует, либо **false** - если контроллер отсутствует.
     */
    public static function exists(string $controller_name, bool $generate_full_names = true)
    {
        if($generate_full_names)
            $controller_name = Controller::className($controller_name);

        $controller_file_path = $_SERVER['DOCUMENT_ROOT'] . $controller_name . ".php";
        if(file_exists($controller_file_path))
            return class_exists($controller_name);
        
        return false;
    }

    /** 
     * Проверяет существование контроллера и наличие у него события.
     * 
     * @param string $controller_name Имя контроллера.
     * @param string $action_name Имя события.
     * @param bool $generate_full_names [опционально]
     * 
     * Нужно ли генерировать полные имена, при помощи методов `Controller::actionName` 
     * и `Controller::className`, прежде чем осуществлять проверку.
     * 
     * @return bool Возвращает **true** - если событие существует, либо **false** - если событие отсутствует.
     */
    public static function actionExists(string $controller_name, string $action_name, bool $generate_full_names = true) {
        if(Controller::exists($controller_name, $generate_full_names)) {
            if($generate_full_names) {
                $controller_name = Controller::className($controller_name);
                $action_name = Controller::actionName($action_name);
            }
            return method_exists($controller_name, $action_name);
        } else
            return false;
    }

    /** 
     * Получение полного имени события контроллера.
     * 
     * Получает сокращенное имя события *( Например: index )*
     * и возвращает его полное имя *( Например: indexAction )*.
     * 
     * Метод использует константы `DIR_CONTROLLERS`, `KEYWORD_ACTION` и параметр `add_keyword_to_objects`
     * **манифеста**, чтобы сгенерировать полное имя события.
     * 
     * @param string $action_name Сокращенное имя события.
     * 
     * @return string Полное имя события.
     */
    public static function actionName(string $action_name)
    {
        $action_fullname = Application::$manifest['add_keyword_to_objects'] ? $action_name . KEYWORD_ACTION : $action_name;
        return $action_fullname;
    }
}
