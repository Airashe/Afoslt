<?php
/**
 * Application.php - описывает класс приложения фреймвора Afoslt.
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
namespace Afoslt\Core;

/**
 * Класс приложения Afoslt.
 */
class Application
{
    /**
     * Конструктор класса приложения.
     * 
     * @author Артем Хиценко <eblludu247@gmail.com>
     * @return Application
     */
    public final function __construct()
    {
        $this->Main();
    }

    /**
     * Входная точка приложения Afoslt. 
     */
    public final function Main ()
    {
        print("Hello World!");
    }
}