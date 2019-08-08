<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Hash;
use App\Core\Redirect;
use App\Core\Request;
use App\Core\Session;
use App\Models\User;

class RegisterController extends Controller
{
    public function index()
    {
        $this->render('register');
    }
    
    public function register(Request $request)
    {
        if(false === $request->exists('post')) {
            Redirect::to('/register');
        }
        
        $user = new User();
        if(!empty($user->first(['username' => $request->post('username')]))) {
            Session::flash('register.errors', 'le pseudo ' . $request->post('username') . ' exists deja sur notre platforme');
            Redirect::to('/register');
        }

        $user->username = $request->post('username');
        $user->password = Hash::make($request->post('password'));
        $user->create();
        
        $auth = new Auth();
        if(true === $auth->login($request->post('username'), $request->post('password'))) {
            $auth->getUser()->online(1);
            Redirect::to('/');
        }
        
        Session::flash('register.errors', 'Un probleme est survenu lors de l\'inscription');
        Redirect::to('/register');
    }
    
    public function logout()
    {
        $auth = new Auth();
        $auth->logout();
        
        Redirect::to('/');
    }
}
