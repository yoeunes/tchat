<?php

namespace App\Controllers;

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
