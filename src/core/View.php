<?php
/**
 * View.php - describes the Afoslt framework view class.
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
 * View for websites based on MVC pattern 
 * powered by Afoslt core.
 * 
 * @author Artem Khitsenko <eblludu247@gmail.com>
 */
class View
{
    // Fields ------------------------------------------

    /**
     * Name of file that will be used as view.
     * 
     * Relative to views directory.
     * 
     * @var string
     */
    private $viewName = "";
    /**
     * Associative array of variables.
     * 
     * That view will be using.
     * 
     * @var array
     */
    private $arguments = [];
    /**
     * Title of view.
     * 
     * If set to `null` will get default value from manifest.
     * 
     * Will be put in tag `<title>`.
     * 
     * @var string|null
     */
    private $title = null;

    // Properties --------------------------------------

    /**
     * Associative array of variables.
     * 
     * That view will be using.
     * 
     * @param array|null    $arguments    Associative array of variables for view.  
     * 
     * @return void
     */
    public final function SetArguments (?array $arguments): void
    {
        $this->arguments = $arguments;
    }

    /**
     * Associative array of variables.
     * 
     * That view will be using.
     * 
     * @return array
     */
    public final function GetArguments (): array
    {
        return $this->arguments;
    }

    /**
     * Title of view.
     * 
     * If set to `null` will get default value from manifest.
     * 
     * Will be put in tag `<title>`.
     * 
     * @param null|string   $title      Title of view.
     * 
     * @return void
     */
    public final function SetTitle (?string $title): void
    {
        $this->title = $title;
    }

    /**
     * Title of view.
     * 
     * If set to `null` will get default value from manifest.
     * 
     * Will be put in tag `<title>`.
     * 
     * @return null|string
     */
    public final function GetTitle (): string
    {
        return $this->title;
    }

    // Methods -----------------------------------------

    /**
     * View for websites based on MVC pattern 
     * powered by Afoslt core.
     * 
     * @param string            $viewName       Name of file that will be rendered.
     * @param null|array        $arguments      [*optional*] Associative array of variables.
     * @param null|string       $title          [*optional*] Title of view.
     * 
     * @author Artem Khitsenko <eblludu247@gmail.com>
     */
    public function __construct(string $viewName, ?array $arguments = null, ?string $title = null)
    {
        $this->viewName = $viewName;
        $this->arguments = $arguments;
        $this->title = $title;
    }

    /**
     * Render view to the client.
     * 
     * @return void
     */
    public final function Render (): void
    {
        if(empty($this->title))
            if(array_key_exists('name', Application::GetManifest()))
                $this->SetTitle(Application::GetManifest()['name']);
            else
                $this->SetTitle("No Title");

        if(is_array($this->GetArguments()))
            extract($this->arguments);

        if(!empty(Application::GetLayout())) {
            echo 1;
        } else {
            echo "Application layout";
        }
    }

    /**
     * Check if view exists.
     * 
     * @return bool
     * Returns `true` if view file exists.
     */
    public static final function Exists (): bool
    {
        return false;
    }
}