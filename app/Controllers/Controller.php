<?php

namespace App\Controllers;

class Controller
{
    protected $vars   = [];
    
    protected $layout = 'default';
    
    public function set($var)
    {
        $this->vars = array_merge($this->vars, $var);
    }
    
    public function render($filename)
    {
        extract($this->vars);
        
        ob_start();
        require APP_PATH.'views/'.$filename.'.php';
        
        $content = ob_get_clean();
        
        if (false === $this->layout) {
            $content;
        } else {
            require APP_PATH.'views/layouts/'.$this->layout.'.php';
        }
    }
    
    public function index()
    {
        dd(__FILE__.':'.__LINE__, $this);
    }
}
