<?php
/**
 * Application.php - описывает класс приложения.
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
 * Приложение работает в режиме **Резила**. 
 * @var int 0
 */
const BUILD_RELEASE = 0;
/** 
 * Приложение работает в режиме **Отладки**.
 * @var int 1
 */
const BUILD_DEBUG = 1;
/** 
 * Конфигурационный файл с маршрутами.
 * @var string **routes**
 */
const CONFIG_ROUTES = 'routes';
/**
 * Конфигурационный файл с манифестом.
 * @var string **manifest**
 */
const CONFIG_MANIFEST = 'manifest';
/** 
 * Родительская директория с конфигурационными файлами приложения.
 * @var string /app/cfg/
 */
const DIR_CONFIGS = DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "cfg" . DIRECTORY_SEPARATOR;
/** 
 * Родительская директория с контроллерами приложения.
 * @var string /app/controllers/
 */
const DIR_CONTROLLERS = DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR;
/** 
 * Родительская директория с представлениями приложения.
 * @var string /app/views/
 */
const DIR_VIEWS = DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR;
/** 
 * Родительская директория с схемами приложения.
 * @var string /app/layouts/
 */
const DIR_LAYOUTS = DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR;
/** 
 * Ключевое слово для контроллеров.
 * @var string **Controller**
 */
const KEYWORD_CONTROLLER = 'Controller';
/** 
 * Ключевое слово для событий. 
 * @var string **Action**
 */
const KEYWORD_ACTION = 'Action';
/** 
 * Маршрут, для ошибки приложения.
 * @var string **app/error**
 */
const ROUTE_APP_ERROR = 'app/error';
/** 
 * Имя слоя используемого по умолчанию.
 * @var string **default**
 */
const LAYOUT_DEFAULT = 'default';

/**
 * Класс содержащий логику приложения.
 * @author Артем Хиценко <eblludu247@gmail.com>
 */
abstract class Application
{
    /** 
     * Параметры манифеста приложения.
     * 
     * **Обязательный конфигурационный файл.**
     * 
     * Манифест хранит настройки ядра приложения и является
     * **обязательным** для подключения. Приложение будет
     * искать все необходимые настройки внутри этого 
     * конфигурационного файла.
     */
    public static $manifest = [];
    /** 
     * Массив маршрутов приложения.
     * 
     * **Обязательный конфигурационный файл.**
     * 
     * Маршуты хранят все доступные статические маршруты приложения,
     * именно по этому списку будет совершен поиск в первую очередь.
     * 
     * Если приложение не найдет маршрут, то при параметре 
     * `dynamic_routes` манифеста, установленном в значение **true**,
     * приложение будет искать необходимый контроллер динамически.
     */
    public static $routes = [];
    /** 
     * Конфигруационные параметры приложения.
     * 
     * Массив с необязательными кофигурационными параметрами.
     * Сюда записываются все параметры, подключаемые из 
     * нестандартных конфигурационных файлов.
     */
    public static $configuration = [];

    /**
     * Имя текущего контроллера приложения.
     */
    public static $controller = '';
    /** 
     * Событие, которое приложение вызывает у текущего
     * контроллера.
     */
    public static $action = '';

    /**
     * Массив аргументов, с которыми было вызвано приложение.
     * 
     * В *массиве аргументов приложения* содержатся параметры 
     * переданные пользователем непосредственно из маршрутов.
     * 
     * Параметр `post_to_arguments` манифеста, установленный в
     * значение **true** позволяет записать параметры переданные
     * через метод POST, в *массив аргументов приложения*.
     * При этом эти же аргументы все еще будут доступными через 
     * массив **$_POST**.
     * 
     * Параметр `get_to_arguments` манифеста, установленный в 
     * значение **true** позволяет записать параметры переданные
     * через метод GET, в *массив аргументов приложения*.
     * При этом эти же аргументы все еще будут доступными через 
     * массив **$_GET**.
     * 
     * Если оба параметра манифеста записываются в 
     * *массив аргументов приложения*, следует проводить контроль
     * за тем, чтобы имена переменных не дублировались, при
     * поступлении из разных источников.
     * 
     * Если маршруты или другие методы передачи аргументов,
     * будут иметь ключи с одинаковыми названиями, то будет
     * происходить перезапись по приоритету источника.
     * 
     * Приоритет источника:
     * 
     * 1. **POST**
     * 2. **GET**
     * 3. **Routes**
     */
    public static $arguments = [];
    /** Массив файлов, которые были переданы приложению, 
     * при вызове.
     * 
     * Параметр `files_to_app` манифеста установленный 
     * в значение **true** заставляет, приложение 
     * скопировать массив $_FILES в *массив загруженных 
     * файлов приложения*.
     */
    public static $uploadedFiles = [];

    /** 
     * Конструктор класса приложения.
     * 
     * Содержит логику инициализация приложения.
     * 
     * @exceptions
     * + *Exception* - Не найден конфигурационный файл **манифеста** приложения.
     * + *Exception* - Не найден конфигурационный файл 
     * маршрутов приложения.
     */
    public function __construct()
    {
        if(!Application::loadConfig(CONFIG_MANIFEST))
            throw new Exception("Can not find application manifest configuration file", 1);
        if(!Application::loadConfig(CONFIG_ROUTES))
            throw new Exception("Can not find application routes configuration file", 2);
        
        $this->readManifest();
        $this->readRequest();
        $this->main();
    }

    /**
     * Загрузка конфигурационного файла из директории конфигурационных файлов.
     * 
     * В случаи подгрузки ключевых конфигурационных файлов, таких как
     * `CONFIG_MANIFEST`, либо `CONFIG_ROUTES` задает их значения в 
     * соответствующие поля приложения.
     * Для всех остальных конфигурационных файлов, параметры будут 
     * добавлены в статический массив `$configuration`.
     * 
     * @param string $cfg_name Имя файла конфигурации *(Без расширения)*.
     * 
     * @return bool Возвращает **true** - если загрузка произведена, 
     * либо **false** в случаи неудачи. 
     */
    public static function loadConfig(string $cfg_name)
    {
        $cfg_path = $_SERVER['DOCUMENT_ROOT'] . DIR_CONFIGS . $cfg_name . ".php";
        if(file_exists($cfg_path)){
            switch($cfg_name) {
                case CONFIG_MANIFEST:
                    Application::$manifest = require $cfg_path;
                    break;
                case CONFIG_ROUTES:
                    $routes = require $cfg_path;
                    foreach($routes as $route => $route_params)
                        Application::addRoute($route, $route_params);
                    break;
                default:
                    $cfg_params = require $cfg_path;
                    foreach($cfg_params as $param_name => $param_val)
                        Application::$configuration[$param_name] = $param_val;
                    break;
            }
            return true;
        }
        else 
            return false;
    }

    /** 
     * Начальная настройка среды, основанная на параметрах 
     * **манифеста**.
     * 
     * Выполняет необходимую настройку приложения и среды 
     * выполнения, основываясь на значениях полученных из 
     * **манифеста**.
     * 
     * Выполняется перед настройкой из запроса пользователя 
     * (`readRequest`) и передачей управления методу `main`.
     * 
     * На основании данных из конфигурационного файла манифеста, 
     * изменяет базовые состояния приложения. Такие, как:
     * + Включение, или отключение отображения ошибок.
    */
    protected function readManifest()
    {
        error_reporting(E_ALL);
        switch(Application::$manifest['build']) {
            case BUILD_RELEASE:
                ini_set('display_errors', 0);
                break;
            case BUILD_DEBUG:
                ini_set('display_errors', 1);
                break;
        }
    }

    /**
     * Начальная настройка среды, основанная на запросе пользователя.
     * 
     * Выполняет необходимую настройку приложения и среды выполнения,
     * основываясь на значениях полученных из запроса пользователя.
     * 
     * Выполняется перед передачей управления методу `main`, 
     * но после `readManifest`.
     * 
     * Получает запрос пользователя из `$_SERVER['REQUEST_URI']` и 
     * обрабатывает его. После чего производит поиск по статическим 
     * маршрутам. Если маршрут найден, то считывает параметры 
     * согласно строению маршрута.
     * 
     * Например:
     * Маршрут: *index/{page}*
     * 
     * Запрос: *index/4*
     * 
     * Будет обработан и вызван контроллер, аргумент 4 будет присвоен
     * ключу **page** в массиве `$arguments`.
     * 
     * Если статический маршрут не найден, то при параметре `dynamic_routes` 
     * манифеста, установленном в значение **true**, метод попытается
     * найти контроллер основываясь на запросе. Все аргументы маршрута
     * будут считаны по принципу **ключ**\\**значение**.
     * 
     * Например:
     * 
     * Маршрут и запрос: *ftbox/main/index/doc/4*
     * 
     * Будет обработан и вызван контроллер, аргумент 4 будет присвоен
     * ключу **doc** в массиве `$arguments`.
     * 
     * Все параметры переданные методом GET будут игнорироваться,
     * при поиске маршрута. При параметре `get_to_arguments` манифеста,
     * установленном в значение **true** все переданные параметры
     * будут записанны в массиве `$arguments`.
     * 
     * Все параметры переданные методом POST, при параметре 
     * `get_to_arguments` манифеста, установленном в значение 
     * **true** будут записанны в массиве `$arguments`.
     * 
     * Все файлы загруженные на сервер, при параметре `files_to_app` 
     * манифеста, установленном в значение **true**, будут скопированны 
     * из массива `$_FILES` в поле `Application::$uploadedFiles`.
     * 
     */
    protected function readRequest()
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $uri = explode('?', $uri)[0];

        $route_readed = false;
        $controller_name = '';
        $action_name = '';

        foreach(Application::$routes as $route => $route_params) {

            if(preg_match($route, $uri, $route_mathces) && is_array($route_params) &&
               array_key_exists('controller', $route_params) && 
               array_key_exists('action', $route_params)
               ) {

                $controller_name = $route_params['controller'];
                $action_name = $route_params['action'];

                foreach($route_mathces as $m_key => $m_value) {
                    if(is_string($m_key)) {
                        Application::$arguments[$m_key] = $m_value;
                    }
                }

                $route_readed = true;
            }
        }

        if(!$route_readed && Application::$manifest['dynamic_routes']) {  
            $uri_parts = explode('/', $uri);
            $uri_count = count($uri_parts);
            if($uri_count >= 2) {
                $controller_full_name = '';
                $controller_data_index = 0;
                while($controller_data_index < $uri_count - 1) {

                    $controller_full_name = !$controller_full_name ? 
                        $uri_parts[$controller_data_index] : 
                        ($controller_full_name . DIRECTORY_SEPARATOR . $uri_parts[$controller_data_index]);

                    $controller_name = $controller_full_name;
                    $action_name = $uri_parts[$controller_data_index + 1];

                    if(Application::controllerExists($controller_name)) {
                        $arguments_index = $controller_data_index + 2;

                        while($arguments_index < $uri_count - 1) {
                            
                            $arg_key = $uri_parts[$arguments_index];
                            $arg_val = $uri_parts[$arguments_index + 1];
                            Application::$arguments[$arg_key] = $arg_val;

                            $arguments_index += 2;
                        }

                        $route_readed = true;
                        break;
                    }

                    $controller_data_index += 1;
                }
            }
        }

        if(Application::$manifest['get_to_arguments'])
            foreach($_GET as $g_key => $g_value)
                Application::$arguments[$g_key] = $g_value;
        if(Application::$manifest['post_to_arguments'])
            foreach($_POST as $p_key => $p_value)
                Application::$arguments[$p_key] = $p_value;

        if(Application::$manifest['files_to_app'])
            foreach($_FILES as $f_key => $f_value)
                Application::$uploadedFiles[$f_key] = $f_value;

        if($route_readed &&
           Application::actionExists($controller_name, $action_name)) {
            Application::$controller = $controller_name;
            Application::$action = $action_name;
        } else
            $this->dropError(404);
    }

    /** 
     * Проверяет существование контроллера.
     * 
     * @param string $controller_name Имя контроллера.
     * @param bool $generate_full_names [опционально]
     * 
     * Нужно ли генерировать полные имена, при помощи метода и 
     * `getControllerClassName`, прежде чем осуществлять проверку.
     * 
     * @return bool Возвращает **true** - если контроллер существует, либо **false** - если контроллер отсутствует.
     */
    public static function controllerExists(string $controller_name, bool $generate_full_names = true)
    {
        if($generate_full_names)
            $controller_name = Application::getControllerClassName($controller_name);

        $controller_file_path = $_SERVER['DOCUMENT_ROOT'] . $controller_name . ".php";
        if(file_exists($controller_file_path))
            return class_exists($controller_name);
        else
            return false;
    }
    /** 
     * Проверяет существование контроллера и наличие у него события.
     * 
     * @param string $controller_name Имя контроллера.
     * @param string $action_name Имя события.
     * @param bool $generate_full_names [опционально]
     * 
     * Нужно ли генерировать полные имена, при помощи методов `getActionName` 
     * и `getControllerClassName`, прежде чем осуществлять проверку.
     * 
     * @return bool Возвращает **true** - если событие существует, либо **false** - если событие отсутствует.
     */
    public static function actionExists(string $controller_name, string $action_name, bool $generate_full_names = true) {
        if(Application::controllerExists($controller_name, $generate_full_names)) {
            if($generate_full_names) {
                $controller_name = Application::getControllerClassName($controller_name);
                $action_name = Application::getActionName($action_name);
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
    public static function getActionName(string $action_name)
    {

        $action_fullname = Application::$manifest['add_keyword_to_objects'] ? $action_name . KEYWORD_ACTION : $action_name;
        return $action_fullname;
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
    public static function getControllerClassName(string $controller_name)
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
     * Добавление нового статического маршрута приложения.
     * 
     * Добавляет новый статический маршрут к статическому массиву `$routes` приложения.
     * Добавленный маршрут будет существовать, пока экземпляр приложения не завершит 
     * работу. Конфигурационный файл статических маршрутов при этом не будет затронут.
     * 
     * @param string $route Адрес маршрута.
     * @param array $params Параметры маршрута.
     * Вида **['controller' => '%controller_name%', 'action' => '%action_name%']**
     * 
     * Где:
     * 
     * **%controller_name%** - Имя создаваемого контроллера.
     * 
     * **%action%** - Имя метода, который будет вызван.
     */
    protected static function addRoute(string $route, $params)
    {
        $route = preg_replace('#\{(\w+)\}#', '(?P<${1}>\w+)', $route);
        $route = '#^' . $route . "$#";
        Application::$routes[$route] = $params;
    }

    /**
     * Обработчик события возникновения ошибки.
     * 
     * @param int $error_code Код ошибки.
     * 
     * @return bool Возвращает **true** - если обработка завершилась
     * успешно.
     * 
     * Возвращает **false** - если обработка ошибки завершилась 
     * неудачей.
     * 
     * @exceptions Исключения:
     * + *Exception* - Не удалось найти контроллер, для обработки 
     * ошибки с заданным кодом.
     */
    protected function dropError(int $error_code)
    {
        $route_name = '#^' . $error_code . '$#';
        $route_app_error = '#^' . ROUTE_APP_ERROR . '$#';
        if($error_code > 599)
            $route_name = $route_app_error;

        if(key_exists($route_name, Application::$routes)) {
            Application::$controller = Application::$routes[$route_name]['controller'];
            Application::$action = Application::$routes[$route_name]['action'];
            return true;
        } else {
            $route_name = $route_app_error;
            Application::$controller = Application::$routes[$route_name]['controller'];
            Application::$action = Application::$routes[$route_name]['action'];
            throw new Exception("Can not find controller for error code(" . $error_code . ")", 3);
        }
        return false;
    }

    /** 
     * Входная точка приложения.
     * 
     * Метод `main` класса `Application` выполняет
     * проверку, что поля `Application::$controller` и 
     * `Application::$action` не пустые, после чего 
     * создает новый экземпляр запрашиваемого контроллера 
     * и вызывает на нем запрашиваемое событие, используя 
     * соответствующие поля.
     * 
     * @exceptions Исключения:
     * + *Exception* - Не удалось найти контроллер или 
     * событие контроллера.
     */
    protected function main()
    {
        if(!Application::$controller == false && !Application::$action == false) {
            $controller_name = Application::getControllerClassName(Application::$controller);
            $action_name = Application::getActionName(Application::$action);
            if(Application::actionExists($controller_name, $action_name, false)) {
                $controller = new $controller_name();
                $controller->$action_name();
            } else {
                throw new Exception("Controller(" . $controller_name . ") or action (" . $action_name . ") not exists.", 42);
            }
        }
    }
}
