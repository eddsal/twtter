<?php
namespace UserManager;

use Cool\DBManager;


class User {

//check input of my email ......
    public function checkInput($var){
        $var = htmlspecialchars($var);
        $var = trim($var);
        $var = stripcslashes($var);

        return $var;
    }
        //check if we have the user mail and password in the db
    public function login($email, $password){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare("SELECT `id` FROM `users` WHERE `email` = :email AND `password` = :password");
        $stmt->bindParam(":email",$email);
        $pass = md5($password);
        $stmt->bindParam(":password", $pass);
        $stmt->execute();

        $user = $stmt->fetch();
        $count = $stmt->rowCount();

        if($count > 0){
            $_SESSION['user_id'] = $user->id;
            header('location:home.html.twig');
        }else{
            return false;
        }
    }
    //insertin user data into my database + default photo and cover
    public function register($email, $screenName, $password){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare("INSERT INTO `users` (`id`,`email`,`password`,`screenName`,`profileImage`,`profileCover`,`following`,`followers`,`bio`,`country`,`website`) VALUES (NULL,NULL,:email, :password, :screenName, 'assets/images/defaultprofileimage.png', 'assets/images/defaultCoverImage.png',0,0,'ewew','lebanon','www')");
        $stmt->bindParam(":email",$email);
        $stmt->bindParam(":password", $pass);
        $stmt->bindParam(":screenName",$screenName);
        $stmt->execute();

        $id = $pdo->lastInsertId();
        $_SESSION['user_id'] = $id;

    }
    //check email on registration
    public function checkEmail($email){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare("SELECT `email` FROM `users` WHERE `email` = :email");
        $stmt->bindParam(":email",$email);
        $stmt->execute();

        $count = $stmt->rowCount();

        if($count > 0){
          return true;
        }else{
            return false;
        }


    }       
  
}