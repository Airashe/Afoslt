<?php
/**
 * routes.php - Конфигурационный файл содержащий статические маршруты приложения.
 * PHP Version 7.3.
 * 
 * Возвращает массив маршрутов.
 * Каждый маршрут представлен в виде: 
 * '%request% => ['controller' => '%controller_name%', 'action' => '%action_name%']
 * Где:
 * %request% - Адрес запроса.
 * %controller_name% - Имя создаваемого контроллера.
 * %action% - Имя метода, который будет вызван.
 * Например: 'index' => ['controller' => 'main', 'action' => 'index'], 
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
    \App\Core\ROUTE_APP_ERROR => ['controller' => 'ftbox\error', 'action' => 'applicationError'], 
    '404' => ['controller' => 'ftbox\error', 'action' => 'show404'], 
    '403' => ['controller' => 'ftbox\error', 'action' => 'show403'], 
    '' => ['controller' => 'ftbox\main', 'action' => 'index'], 
    'index' => ['controller' => 'ftbox\main', 'action' => 'index'], 
];
