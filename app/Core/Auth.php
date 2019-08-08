<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    private $isLogged = false;
    
    private $user     = null;
    
    public function __construct()
    {
        $this->user = new User();
        if (Session::exists('user')) {
            $us = Session::get('user');
            $this->user->find($us['id']);
            $this->isLogged = true;
        }
    }
    
    public function login($email = null, $password = null)
    {
        //sleep(2);
        
        if (($email === null || $password === null) && Session::exists('user')) {
            $us = Session::get('user');
            $this->user->find($us['id']);
            $this->isLogged = true;
            
            return $this->isLogged;
        }
        
        $user = $this->user->first(
            [
                'email'    => $email,
                'password' => Hash::make($password),
            ]
        );
        
        if ($user && count($user) > 0) {
            $this->isLogged = true;
            $session        = $user;
            if (isset($session['password'])) {
                unset($session['password']);
            }
            Session::put('user', $session);
        }
        
        return $this->isLogged;
    }
    
    public function logout()
    {
        $this->isLogged = false;
        Session::delete('user');
    }
    
    public function isLogged()
    {
        return $this->isLogged;
    }
}
