<?php
define("Z_MVC",1);
set_time_limit(0);
session_start();

spl_autoload_register(function($classname){
    $class_map = require 'config/maps.php';
    if(isset($class_map[$classname]))
    {
        $filename = $class_map[$classname].'.php';

        if(file_exists($filename))
        {
            require $filename;
            return;
        }
    }
    else
    {
        $explode_classname = explode('\\',$classname);
        if($explode_classname[0] == 'App')
            $classname = str_replace('App\\','',$classname);
        $classname = str_replace('\\','/',$classname);
        $filename = 'applications/'.$classname.'.php';
        if(file_exists($filename))
        {
            require $filename;
            return;
        }
    }    
    die('404 File '.$filename.' Not Found');
});

require "system/libraries/Functions.php";
$app = require "config/applications.php";

$URI = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
$URI = parse_url($URI,PHP_URL_PATH);
$_URI = explode("/", $URI);
if($app['root_dir'] && $_URI[1] == $app['root_dir'])
    unset($_URI[1]);
$URI = implode("/", $_URI);
$URI = trim($URI,'/');
$URI = empty($URI) ? '/' : $URI;


if($URI == '/')
{
    $controller = $app['app_namespace'] . "\\" . $app['main_controller'];
    $callback = (new $controller)->index();
}
else
{
    $url = explode('/',$URI);
    $url = array_map(function($_url){
        return ucfirst($_url);
    }, $url);
    if(count($url) ==  1) // just controller
    {
        $URI = str_replace('/','\\',$URI);
        $URI = $URI . "Controller";
        $controller = $app['app_namespace'] . "\\Controllers\\" . $URI;
        $callback = (new $controller)->index();
    }
    elseif(count($url) == 2) // controller and method
    {
        $controller = $app['app_namespace'] . "\\Controllers\\" . $url[0] . "Controller";
        $method     = $url[1];

        $_controller  = array_slice($url, 0, count($url)-1);
        $_controller  = implode("/", $_controller);
        $file         = 'applications/Controllers/'.$_controller .'Controller.php';

        if(file_exists($file))
        {
            if(method_exists($controller,$method))
                $callback = (new $controller)->{$method}();
            else
                $callback = elseCallback();
        }
        else
            $callback = elseCallback();
    }
    elseif(count($url) == 3) // controller, method and args
    {
        $controller = $app['app_namespace'] . "\\Controllers\\" . $url[0] . "Controller";
        $method     = $url[1];

        $_controller  = array_slice($url, 0, count($url)-1);
        $_controller  = implode("/", $_controller);
        $file         = 'applications/Controllers/'.$_controller .'Controller.php';

        if(file_exists($file))
        {
            $_controller = str_replace("/", "\\", $_controller);
            $controller = $app['app_namespace'] . "\\Controllers\\" . $_controller . "Controller";
            $method     = end($url);
            if(method_exists($controller,$method))
                $callback = (new $controller)->{$method}();
            else
                $callback = elseCallback();
        }
        else
            if(method_exists($controller,$method))
                $callback = (new $controller)->{$method}($url[2]);
            else
                $callback = elseCallback();
    }
    else // with namespace
    {
        // check file exists
        $controller  = array_slice($url, 0, count($url)-1);
        $_controller = implode("/", $controller);
        $file        = 'applications/Controllers/'.$_controller .'Controller.php';
        if(file_exists($file))
        {
            $controller = implode("\\", $controller);
            $controller = $app['app_namespace'] . "\\Controllers\\" . $controller . "Controller";
            $method     = end($url);
            if(method_exists($controller,$method))
                $callback = (new $controller)->{$method}();
            else
            {
                $callback = elseCallback();
            }
        }
        else
        {
            $controller = array_slice($url, 0, count($url)-2);
            $controller = implode("\\", $controller);
            $controller = $app['app_namespace'] . "\\Controllers\\" . $controller . "Controller";
            $method     = $url[count($url)-2];
            $args       = end($url);
            $callback   = (new $controller)->{$method}($args);
        }
    }   
}

function elseCallback()
{
    global $app;
    global $url;
    $controller = implode("\\", $url);
    $controller = $app['app_namespace'] . "\\Controllers\\" . $controller . "Controller";
    $callback = (new $controller)->index();
    return $callback;
}

if(is_array($callback) || is_object($callback))
{
    header("content-type: application/json");
    $callback = json_encode($callback);
}

echo $callback;


// $boot = new Boot;