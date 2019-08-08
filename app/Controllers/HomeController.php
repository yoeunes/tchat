<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $user = new User();
        dd($user->all());
        $this->render('home');
    }
    
    public function json()
    {
        $this->render('home');
    }
}
