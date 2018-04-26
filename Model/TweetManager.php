<?php
namespace TweetManager;

use Cool\DBManager;
use UserManager\User;

class Tweet extends User {

    public function tweets(){
        $dbManager = DBManager::getInstance();
        $pdo = $dbManager->getPdo();
        $stmt =$pdo->prepare("SELECT * FROM `tweets`");
        $stmt->execute();
        $tweets = $stmt->fetchAll();

       return $tweets;
    }

   
}