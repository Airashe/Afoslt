<?php

use App\Core\Application;

?>

<h1><?=$message?></h1>
<pre><b>Application manifest:</b> <?var_dump(Application::$manifest)?></pre>
<pre><b>Application configuration:</b> <?var_dump(Application::$configuration)?></pre>
<pre><b>Application routes:</b> <?var_dump(Application::$routes)?></pre>
<pre><b>Passed arguments:</b> <?var_dump(Application::$arguments)?></pre>
<pre><b>Uploaded files:</b> <?var_dump(Application::$uploadedFiles)?></pre>
<pre><b>Current controller:</b> <?var_dump(Application::$controller)?></pre>
<pre><b>Current action:</b> <?var_dump(Application::$action)?></pre>
<pre><b>Loaded models:</b> <?var_dump($loadedModels)?></pre>