<?php

function app($key = false)
{
    $app    = require 'config/applications.php';
    if($key != false)
        return $app[$key];
    return $app;
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function base_url()
{
    $app    = app();
    $application_protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $base_url = $application_protocol.$_SERVER['SERVER_NAME'];
    if($_SERVER['SERVER_PORT'])
        $base_url .= ':'.$_SERVER['SERVER_PORT'];
    if($app['root_dir'])
        $base_url .= '/'.$app['root_dir'];
        
    return $base_url;
}

function asset($path)
{
    return base_url().'/public/'.$path;
}

function old($key)
{
    return isset($_SESSION["old"][$key]) ? $_SESSION["old"][$key] : "";
}

function showError($message, $type = 404)
{
    $app    = app();
    $filename = $app['template_path'].$app['template_active'].'/errors/'.$type.'.php';
    if(file_exists($filename))
        require $filename;
    else
        echo "<h2>Error $type</h2><p>$message</p>";
    die();
}

function history()
{
    return new History;
}

function request()
{
    return new Request;
}

function session()
{
    return new Session;
}

function redirect($url)
{
    $url = base_url().$url;
    header('location:'.$url);
    die();
}


function strWordCut($string,$length,$end='....')
{
    $string = strip_tags($string);

    if (strlen($string) > $length) {

        // truncate string
        $stringCut = substr($string, 0, $length);

        // make sure it ends in a word so assassinate doesn't become ass...
        $string = substr($stringCut, 0, strrpos($stringCut, ' ')).$end;
    }
    return $string;
}

function slug($string)
{
    $str = strWordCut($string,7,'');
    $str = strtolower($str);
    $str = str_replace(" ","-",$str);

    return $str;
}

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function template_active_path()
{
    return Template::getTemplateActive();
}

function add_custom_post($name,$label,$icon = "fa-file")
{
    CustomPost::add($name,$label,$icon);
}

function view($file, $data = false)
{
    $active = app()['template_active'];
    $file = str_replace(".", "/", $file);
    $file = 'template/'.$active.'/'.$file.'.php';
    $template = new Template;
    return $template->layout($data,$file,$active);
    // return $template->getContent();
}

function partial($file, $data = false)
{
    $active = app()['template_active'];
    $file = str_replace(".", "/", $file);
    $file = 'template/'.$active.'/'.$file.'.php';
    $template = new Template($data,$file,$active);
    return $template->partial($data,$file,$active);
    // return $template->getContent();
}