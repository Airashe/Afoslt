<?php
/**
 * Layout.php - describes the Afoslt framework layout class.
 * PHP Version 7.3.
 *
 * @see       https://github.com/IIpocToTo4Ka/Afoslt - Afoslt GitHub repository.
 *
 * @author    Artem Khitsenko <eblludu247@gmail.com>
 * @copyright Copyright (c) 2020 IIpocTo_To4Ka.
 * @license   MIT License.
 * @package   Afoslt Core
 * @note      This code is distributed in the hope that it can help someone. 
 * The author does not guarantee that this code can somehow work and be useful 
 * or do anything at all.
 */
namespace Afoslt\Core;

/**
 * Layout for websites based on MVC pattern 
 * powered by Afoslt core.
 * 
 * @author Artem Khitsenko <eblludu247@gmail.com>
 */
class Layout
{
    /**
     * Check if layout file exists.
     * 
     * @param ?string    $layoutName     Name of layout file (with directories).
     * 
     * @return bool
     */
    public static function Exists (?string $layoutName): bool
    {
        if(!empty($layoutName)) {
            $layoutName = trim($layoutName, "\\/");
            
            if(preg_match("/.html$/", $layoutName) === 0)
                $layoutName .= ".html";
            
            $layoutPath = PATH_APPLICATION . "src" . DIRECTORY_SEPARATOR . "layouts" . DIRECTORY_SEPARATOR . $layoutName;
            return file_exists($layoutPath);
            }
        return false;
    }
}