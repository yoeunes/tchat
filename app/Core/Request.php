<?php

namespace App\Core;

class Request
{
    /** @var string */
    protected $url;
    
    /** @var string */
    protected $controller;
    
    /** @var string */
    protected $action;
    
    public function __construct()
    {
        if(!isset( $_SERVER['REDIRECT_URL'])) {
            $this->url = $_SERVER['REQUEST_URI'];
        } else {
            $this->url = $_SERVER['REDIRECT_URL'];
        }

        $explodeUrl = array_values(array_filter(explode('/', trim($this->url))));
        $this->controller = isset($explodeUrl[0]) ? $explodeUrl[0] : 'home';
        $this->action = isset($explodeUrl[1]) ? $explodeUrl[1] : 'index';
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * @return string
     */
    public function getController()
    {
        $controller = 'App\\Controllers\\' . ucfirst($this->controller).'Controller';
        
        return new $controller;
    }
    
    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
    
    public static function exists($type = 'post')
    {
        switch (strtolower($type)) {
            case 'post' :
                return !empty($_POST);
            case 'get'  :
                return !empty($_GET);
            default:
                return false;
        }
    }
    
    public static function get($param)
    {
        if(false === self::exists('get')) {
            return null;
        }
        
        return isset($_GET[$param]) ? $_GET[$param] : null;
    }

    public static function post($param)
    {
        if(false === self::exists('post')) {
            return null;
        }
        
        return isset($_POST[$param]) ? $_POST[$param] : null;
    }
}
