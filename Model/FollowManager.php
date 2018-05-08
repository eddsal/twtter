<?php
namespace FollowManager;


use Cool\DBManager;
use UserManager\User;

class follow extends User {

public function checkfollow($followerId,$userId){
    $dbManager = DBManager::getInstance();
    $pdo = $dbManager->getPdo();
    $stmt =$pdo->prepare("SELECT * FROM `follow` WHERE `following` = :userId AND `follower` = :followerId");
    $stmt->bindParam(':userId',$userId);
    $stmt->bindParam(':followerId',$followerId);
    $stmt->execute();
    $follow = $stmt->fetch();

    var_dump($follow);

        return $follow;
}

public function followBtn($profileId,$userId){
    $data = $this->checkfollow($profileId,$userId);
    if($_SESSION === true){

    }else{
        echo "<button class='f-btn' onclick=location.href='index.php'><i class='fa fa-user-plus'></i></button>" 
    }

}





}