<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Redirect;

class HomeController extends Controller
{
    public function index()
    {
        $auth = new Auth();

        if (false === $auth->isLogged()) {
            Redirect::to('/login');
        }
        
        $this->render('home');
    }
    
    public function json()
    {
        $this->render('home');
    }
}
