<?php
namespace FollowManager;


use Cool\DBManager;
use UserManager\User;

class Follow extends User {

public function checkfollow($followerId,$userId){
    $dbManager = DBManager::getInstance();
    $pdo = $dbManager->getPdo();
    $stmt =$pdo->prepare("INSERT INTO  `follow` (`folowId`, `following`, `follower`) VALUES (NULL,:userId,:followerId) ");
    $stmt->bindParam(':userId',$userId);
    $stmt->bindParam(':followerId',$followerId);
    $stmt->execute();
    $follow = $stmt->fetch();


        return $follow;
}








}