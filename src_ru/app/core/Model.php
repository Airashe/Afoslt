<?php
/**
 * Model.php - описывает родительский класс для всех моделей
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
 * Родительский класс для всех моделей приложения.
 * 
 * @author Артем Хиценко <eblludu247@gmail.com>
 */
abstract class Model
{
    /**
     * Конструктор нового экземпляра модели.
     */
    public function __construct()
    {
        
    }

    /**
     * Проверяет существование модели.
     * 
     * @param string $model_name Имя модели.
     * @param bool $generate_full_names [опционально]
     * 
     * Нужно ли генерировать полные имена, при помощи метода и 
     * `Model::className`, прежде чем осуществлять проверку.
     * 
     * @return bool Возвращает **true** - если модель существует, либо **false** - если модель отсутствует.
     */
    public static function exists(string $model_name, bool $generate_full_names = true)
    {
        if($generate_full_names)
            $model_name = Model::className($model_name);

        $model_file_path = $_SERVER['DOCUMENT_ROOT'] . $model_name . ".php";
        if(file_exists($model_file_path))
            return class_exists($model_name);

        return false;
    }

    /**
     * Получение полного имени класса модели.
     * 
     * Получает сокращенное имя модели *( Например: main )*
     * и возвращает его полное имя *( Например: \app\models\Main )*.
     * 
     * Метод использует константу `DIR_MODELS`, чтобы сгенерировать полное имя модели.
     * 
     * @param string $model_name Сокращенное имя модели.
     * 
     * @return string Полное имя модели.
     */
    public static function className(string $model_name)
    {
        $model_subdirectory = '';
        if($lastSDPos = strrpos($model_name, '\\')) {
            $model_subdirectory = substr($model_name, 0, $lastSDPos) . DIRECTORY_SEPARATOR;
            $model_name = substr($model_name, $lastSDPos + 1);
        }
        $model_path = DIR_MODELS . $model_subdirectory . ucfirst($model_name);
        return $model_path;
    }
}