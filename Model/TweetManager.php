<?php
namespace TweetManager;

use Cool\DBManager;
use UserManager\User;

class Tweet extends User {
    public function profile(){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();


    }


   
}