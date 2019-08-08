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
        $this->url = $_SERVER['REDIRECT_URL'];
        
        $explodeUrl = array_slice(explode('/', trim($this->url)), 1);
        
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
}
