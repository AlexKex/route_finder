<?php
/**
 * Created by PhpStorm.
 * User: apryakhin
 * Date: 27.01.16
 * Time: 14:35
 */

function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = __DIR__."/";
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= "src/".str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    require $fileName;
}
spl_autoload_register('autoload');