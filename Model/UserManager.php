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
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :email AND `password` = :password");
        $stmt->bindParam(":email",$email);
        $password = md5($password);
        $stmt->bindParam(":password", $password);
        $stmt->execute();

        $user = $stmt->fetch();
        $count = $stmt->rowCount();

        if($count > 0){
         $_SESSION['id'] = $user->id;
        }else{
            return false;
        }
    }
    //insertin user data into my database + default photo and cover
    public function register($username,$email, $password,$screenName,$profileImage,$profileCover,$followers,$following,$bio,$country,$website){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("INSERT INTO `users` (`id`,`username`,`email`,`password`,`screenName`,`profileImage`,`profileCover`,`following`,`followers`,`bio`,`country`,`website`) VALUES (NULL,:username,:email, :password, :screenName, :profileImage,:profileCover ,:following,:followers,:bio, :country, :website)");
        $stmt->bindParam(":username",$username);
        $stmt->bindParam(":email",$email);
        $password = md5($password);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":screenName",$screenName);
        $stmt->bindParam(":profileImage",$profileImage);
        $stmt->bindParam(":profileCover",$profileCover);
        $stmt->bindParam(":following",$following);
        $stmt->bindParam(":followers",$followers);
        $stmt->bindParam(":bio",$bio);
        $stmt->bindParam(":country",$country);
        $stmt->bindParam(":website",$website);
        $stmt->execute();


        $id =   $pdo->lastInsertId();
        $_SESSION['id'] = $id;

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
    //take all user data from database to put it in profile
    public function userData($id){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();

        
        $id = $pdo->lastInsertId();
        $_SESSION['id'] = $id;
    }  
    public function logout() {
        session_destroy();
    }     
  
}