<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Redirect;
use App\Core\Request;
use App\Models\Discussion;

class DiscussionController extends Controller
{
    public function discussion(Request $request)
    {
        $auth = new Auth();

        if (false === $auth->isLogged()) {
            Redirect::to('/login');
        }
        
        $discussion = new Discussion();
        $result = $discussion->getDiscussion($auth->getUser()->id, $request->get('otherUser'));
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok', 'result' => $result]);
    }
    
    public function store(Request $request)
    {
        $auth = new Auth();

        if (false === $auth->isLogged()) {
            Redirect::to('/login');
        }
        
        $discussion = new Discussion();
        $discussion->from_user = $auth->getUser()->id;
        $discussion->to_user = $request->post('otherUser');
        $discussion->message = $request->post('message');
        $discussion->create();
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok']);
    }
    
    public function markAsRead(Request $request)
    {
        $auth = new Auth();

        if (false === $auth->isLogged()) {
            Redirect::to('/login');
        }
        
        $discussion = new Discussion();
        $discussion->markAsRead($auth->getUser()->id, $request->get('otherUser'));
    }
}
