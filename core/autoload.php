<?php

function autoload($className)
{
    if (!preg_match("/^alimmvc\\\/sui", $className)) 
    {
        return false;
    } else {
        $className = preg_replace("/^alimmvc\\\/sui", '', $className);
    }
    // if (preg_match("/^(controllers|models|tests)/sui", $className)) 
    // {
    //     $className = DIR_MVC . "\\" . $className;
    // }

    // $appDir = realpath(dirname(__FILE__));
    // $appDir = preg_replace("/\\".DIRSEP."core/sui", "", $appDir);
    // debug($className);
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) 
    {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRSEP, $namespace) . DIRSEP;
    }
    //$fileName .= str_replace('_', DIRSEP, $className) . '.php';
    $fileName .= $className . '.php';
    // debug($fileName);
    if (!preg_match("/composer/i", $fileName))
        require_once PATH_APP_DIR . DIRSEP . $fileName;
}

spl_autoload_register('autoload');

