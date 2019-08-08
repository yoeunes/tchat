<?php

namespace App\Controllers;

use App\Core\Request;

class HomeController extends Controller
{
    public function index()
    {
        $this->render('home');
    }
    
    public function json()
    {
        $this->render('home');
    }
}
