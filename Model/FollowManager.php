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

public function followBtn($profileId,$userId){
    $data = $this->checkfollow($profileId,$userId);
    if($_SESSION === true){

    }else{
        echo "<button class='f-btn' onclick=location.href='index.php'><i class='fa fa-user-plus'></i></button>" ;
    }

}





}