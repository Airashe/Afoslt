<?php
/**
 * manifest.php - Конфигурационный файл содержащий данные манифеста приложения.
 * PHP Version 7.3.
 * 
 * application_name - Имя приложения.
 * application_version - Версия приложения.
 * core_version - Версия ядра.
 * build - Запускать приложение в режиме **Отладки** (BUILD_DEBUG) или **Релиза** (BUILD_RELEASE).
 * author - Автор(ы) приложения.
 * company - Компания, выпустившая приложение.
 * 
 * dynamic_routes - Использовать динамические маршруты.
 * add_keyword_to_objects - Добавлять ключевые слова к объектам.
 * post_to_arguments - Добавлять параметры из массива $_POST к аргументам.
 * get_to_arguments - Добавлять параметры из массива $_GET к аргументам.
 * files_to_app - Копировать массив $_FILES в массив приложения.
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

return [
    'application_name' => 'My Application',
    'application_version' => '1.0.0.0',
    'core_version' => '0.1.2.24',
    'build' => \App\Core\BUILD_DEBUG,
    'author' => 'Author name',
    'company' => 'Company Name',
    'dynamic_routes' => true,
    'add_keyword_to_objects' => true,
    'post_to_arguments' => true,
    'get_to_arguments' => true,
    'files_to_app' => true
];
