<?php

class Template
{
    public $title = "";
    public $application_name = "";
    public $header = "";
    public $content = "";
    public $navbar = "";
    public $sidebar = "";
    public $footer = "";
    public $app = "";
    public $visited = "";
    public $css = [];
    public $js = [];
    public static $active = "";

    function layout($data, $file, $active)
    {
        $this->app    = app();
        $this->title  = $this->app['application_name'];
        $this->application_name  = $this->app['application_name'];

        if(!empty($data))
            extract($data);

        if(file_exists($file))
        {
            ob_start();
            require $file;
            $content = ob_get_clean();
        }
        else
        {
            $content = "";
        }
        
        ob_start();
        require 'template/'.$active.'/index.php';
        $content = ob_get_clean();
        return $content;
    }

    function getContent()
    {
        return $this->content;
    }

    function partial($data, $file)
    {
        $this->app    = app();
        $this->title  = $this->app['application_name'];
        $this->application_name  = $this->app['application_name'];

        if(!empty($data))
            extract($data);

        if(file_exists($file))
        {
            ob_start();
            require $file;
            $content = ob_get_clean();
        }
        else
        {
            $content = "";
        }

        return $content;
    }

    static function setTemplateActive($path)
    {
        self::$active = $path;
    }

    static function getTemplateActive()
    {
        return self::$active;
    }
}