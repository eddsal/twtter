<?php
namespace TweetManager;

use Cool\DBManager;
use UserManager\User;

class Tweet extends User {
    public function profile(){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();


    }
    public function tweets(){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $this->pdo->prepare("SELECT * FROM `tweets`,`users` WHERE `tweetBy = `id`");
        $stmt->execute();
        $tweets = $stmt->fetchAll();

       return $this;
    }

   
}