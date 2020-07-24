<?php

class History
{

    static function now($route)
    {
        if(!isset($_SESSION['history']))
            $_SESSION['history'] = ['now' => '', 'back' => ''];

        if($_SESSION['history']['now'] == base_url().'/'.$route)
            return;
        
        if($route == '/')
            $route = '';
        
        $_SESSION['history']['back'] = isset($_SESSION['history']['now']) ? $_SESSION['history']['now'] : '';
        $_SESSION['history']['now'] = base_url().'/'.$route;
    }

    static function back()
    {
        return $_SESSION['history']['back'];
    }

    static function print()
    {
        return $_SESSION['history'];
    }

}