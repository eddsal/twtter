<?php
namespace UserManager;

use Cool\DBManager;
use TweetManger\Tweet;


class User {

    //vraeting search methode
  /*  public function search($search){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("SELECT `id`, `username`, `screenName` , `profileImage`, `profileCover` FROM `users` WHERE `username` LIKE ? OR `screenName` LIKE ?");
        $stmt->bindValue(1, $search. '%');
        $stmt->bindValue(2, $search. '%');
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    */
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

        return $user;
    }
    //insertin user data into my database + default photo and cover
    public function register($id,$username,$email, $password,$screenName,$profileImage,$profileCover,$followers,$following,$bio,$country,$website){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);


        $stmt = $pdo->prepare("SELECT `email` FROM `users` WHERE `email` = :email");
        $stmt->bindParam(":email",$email);
        $stmt->execute();


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
             return var_dump($id);

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
    public function logout() {
        session_destroy();
    }

    public function create($table,$fields){
        $table = array();
        $fields = array();
        $columns = implode(',', array_keys($fields));
        $values = ':'.implode(', :', array_keys($fields));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
        
    } 
    public function getUser(){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM `users`");
        $stmt->execute();
        $user = $stmt->fetch();
        
        return $user;
    }
}