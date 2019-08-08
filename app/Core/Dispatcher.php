<?php

namespace App\Core;

class Dispatcher
{
    protected $request;
    
    public function __construct()
    {
        $this->request = new Request();
    }
    
    public function dispatch()
    {
        call_user_func([$this->request->getController(), $this->request->getAction()], $this->request);
    }
}
