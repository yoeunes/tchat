<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Redirect;
use App\Core\Request;
use App\Core\Session;

class LoginController extends Controller
{
    public function index()
    {
        $this->render('login');
    }
    
    public function login(Request $request)
    {
        if(false === $request->exists('post')) {
            Redirect::to('/login');
        }
        
        $auth = new Auth();
        if(true === $auth->login($request->post('username'), $request->post('password'))) {
            $auth->getUser()->online(1);
            
            Redirect::to('/');
        }
        
        Session::flash('login.errors', 'Pseudo ou mot passe invalid');
        Redirect::to('/login');
    }
    
    public function logout()
    {
        $auth = new Auth();
        
        if($auth->getUser()) {
           $auth->getUser()->online(0);
        }
        
        $auth->logout();
        
        Redirect::to('/');
    }
}
