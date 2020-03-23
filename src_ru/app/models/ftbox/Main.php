<?php
/**
 * Main.php - описывает пример модели.
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
namespace App\Models\Ftbox;

use App\Core\Model;

/**
 * Пример модели приложения.
 * 
 * @author Артем Хиценко <eblludu247@gmail.com>
 */
class Main extends Model
{
    /**
     * Сообщение приложения.
     */
    public $message;

    /**
     * Конструктор нового экземпляра модели *Main*.
     
     * + Устанавливает значение поля имени, как **ftbox\main**;
     * + Устанавливает значение поля сообщения, как **Hello, world!**;
     */
    public function __construct()
    {
        $this->message = 'Hello, world!';
    }
}