<?php
/**
 * Autoloader.php - Скрипт отвечающий за обработку автоматической загрузки файлов библиотек.
 * PHP Version 7.3.
 *
 * @author    Артем Хиценко <eblludu247@gmail.com>
 * @copyright Copyright (c) 2020 IIpocTo_To4Ka.
 * @license   MIT License.
 * @package   IIT Autoloader
 * @note      Данный код распространяется с надеждой, что он может кому-то помочь. Автор 
 * не дает гарантию, что это код может хоть как-то работать и приность пользу или вообще 
 * что-либо делать.
 */
namespace iit\autoloader;

/** 
 * Метод автозагрузки библиотек.
 * 
 * На основании namespace запрашиваемого класса, производит поиск 
 * в каталоге **app** *(для классов приложения)*, либо **vendor** 
 * *(для сторонних библиотек)*.
 * 
 * @param string $class_name Полное имя класса, который нужно найти.
 * @return void
 * @exceptions Исключения:
 * + *Exception* - Не удалось обнаружить класс, по предполагаемому пути.
 */
function AutoLoad($class_name)
{
    $class_name = ltrim($class_name, '\\');
    $fileName  = '';
    $namespace = '';
    $directory = '';
    if ($lastNsPos = strrpos($class_name, '\\')) {
        $namespace = substr($class_name, 0, $lastNsPos);
        $class_name = substr($class_name, $lastNsPos + 1);
        $first_namespace = substr($namespace, 0, 3);
        if($first_namespace == 'App' || $first_namespace == 'app') {
            $directory = "app";
            $namespace = substr($namespace, 3);
        }
        else {
            $directory = "vendor" . DIRECTORY_SEPARATOR;
        }
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
    $filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $directory . $fileName;
    if(file_exists($filePath))
        require_once $filePath;
    else {
        throw new \Exception("Can not find class (" . $class_name . ") by location " . $filePath . ". File not exists.", 1001);
    }
}

spl_autoload_register('\iit\autoloader\AutoLoad');
