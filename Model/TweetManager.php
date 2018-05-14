<?php
namespace TweetManager;


use Cool\DBManager;
use UserManager\User;

class Tweet extends User {

    public function tweets(){
        $dbManager = DBManager::getInstance();
        $pdo = $dbManager->getPdo();
        $stmt =$pdo->prepare("SELECT * FROM `tweets`,`users` WHERE `tweetBy` = `id` ORDER BY tweetId DESC");
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
    public function deleteTweet(){
        $dbManager = DBManager::getInstance();
        $pdo = $dbManager->getPdo();
        $stmt = $pdo->prepare("DELETE FROM tweets WHERE tweetID = {$_POST['deleteTweet']} ");
        
        $stmt->execute();
    }

    public function countTweet($id){
        $dbManager = DBManager::getInstance();
        $pdo = $dbManager->getPdo();
        $stmt = $pdo->prepare("SELECT COUNT(`tweetID`) AS `totalTweets` FROM `tweets` WHERE `tweetBy` = :id And `retweetId` = '0' OR `retweetBy` = :id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        $count = $stmt->fetch();

        return $count;


    }
    public function getRetweet($tweetId){
        $dbManager = DBManager::getInstance();
        $pdo = $dbManager->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM `tweets`,`users` WHERE 'tweetID' = $tweetId AND tweetBy= {$_SESSION['id']} ");
        $retweet =  $stmt->execute();
     
        return $retweet;
      
        // die();
       

    }
    public function like($userId,$tweetId){
        $dbManager = DBManager::getInstance();
        $pdo = $dbManager->getPdo();
        $stmt = $pdo->prepare("UPDATE `tweets` SET `likeCount` = `likeCount` +1  WHERE `tweetId` = :tweetId");
        $stmt->bindParam(":tweetId",$tweetId);
        $stmt->execute();
     
        $like = $pdo->prepare("INSERT INTO `likes` (`likeBy` ,`likeOn`) VALUES({$_SESSION['id']} , $tweetId )");
        $like->execute();

        

    }




   
}