<?php
namespace TweetManager;

use Cool\DBManager;
use UserManager\User;

class Tweet extends User {

    public function tweets(){
        $dbManager = DBManager::getInstance();
        $pdo = $dbManager->getPdo();
        $stmt =$pdo->prepare("SELECT * FROM `tweets`,`users` WHERE `tweetBy` = `id`");
        $stmt->execute();
        $tweets = $stmt->fetchAll();
    
            return $tweets;
    
            
    }
    public function tweetByid(){
        $dbManager = DBManager::getInstance();
        $pdo = $dbManager->getPdo();
        $stmt =$pdo->prepare("SELECT * FROM `tweets` WHERE `tweetBy` = {$_SESSION['id']} ");
      
        $stmt->execute();
        $tweets = $stmt->fetchAll();
      //  $tweets=array_column($tweets,'status');
        return $tweets;
    }



   
}