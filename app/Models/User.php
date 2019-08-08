<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';
    
    public function getUsersList($currentUser)
    {
        $sql = 'SELECT DISTINCT users.* FROM users
                LEFT JOIN discussions ON (users.id = discussions.to_user OR users.id = discussions.from_user)
                WHERE
                    (users.id = discussions.to_user AND discussions.from_user=:currentUser) OR
                    (users.id = discussions.from_user AND discussions.to_user=:currentUser) OR
                    (users.online=1 AND users.id<>:currentUser)';
        
        $users = $this->bdd->run($sql, [
            'currentUser' => $currentUser,
        ]);
        
        if(!empty($users)) {
            $usersId = implode(',', array_column($users, 'id'));
            $sql = 'SELECT discussions.from_user, count(discussions.id) as notReadCount FROM users,discussions
                    WHERE
                        users.id = discussions.to_user AND
                        discussions.to_user=:currentUser AND
                        discussions.from_user in ('.$usersId.') AND
                        discussions.to_user_read=0
                    GROUP BY discussions.from_user';
            $notReadCount = $this->bdd->run($sql, [
                'currentUser' => $currentUser,
            ]);
            
            foreach ($users as $index => $user) {
                foreach ($notReadCount as $count) {
                    if ($count['from_user'] == $user['id']) {
                        $users[$index]['notReadCount'] = $count['notReadCount'];
                    }
                }
            }
        }
        
        return $users;
    }
    
    public function online($status)
    {
        $this->online = $status;
        $this->save();
    }
}
