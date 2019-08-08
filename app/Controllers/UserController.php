<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Redirect;
use App\Models\User;

class UserController extends Controller
{
    public function getUsersList()
    {
        $auth = new Auth();

        if (false === $auth->isLogged()) {
            Redirect::to('/login');
        }
        
        $user = new User();
        $result = $user->getUsersList($auth->getUser()->id);
        
        foreach ($result as $index => $user) {
            unset($result[$index]['password']);
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok', 'result' => $result]);
    }
}
