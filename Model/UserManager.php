<?php
namespace UserManager;

use Cool\DBManager;


class User {
    protected $pdo;

    function __construct($pdo){
        $this->pdo = $pdo;
    }
//check input of my email ......
    public function checkInput($var){
        $var = htmlspecialchars($var);
        $var = trim($var);
        $var = stripcslashes($var);

        return $var;
    }
        //check if we have the user mail and password in the db
    public function login($email, $password){
        $stmt = $this->pdo->prepare("SELECT `id` FROM `users` WHERE `email` = :email AND `password` = :password");
        $stmt->bindParam(":email",$email, PDO::PARAM_STR);
        $pass = md5($password);
        $stmt->bindParam(":password", $pass, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_OBJ);
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
        $stmt = $this->pdo->prepare("INSERT INTO `users` (`email`,`password`,`screenName`,`profileImage`,`profileCover`) VALUES (:email, :password, :screenName, 'assets/images/defaultprofileimage.png', 'assets/images/defaultCoverImage.png')");
        $stmt->bindParam(":email",$email, PDO::PARAM_STR);
        $stmt->bindParam(":password", $pass, PDO::PARAM_STR);
        $stmt->bindParam(":screenName",$screenName, PDO::PARAM_STR);
        $stmt->execute();

        $id = $this->pdo->lastInsertId();
        $_SESSION['user_id'] = $id;

    }
    //check email on registration
    public function checkEmail($email){
        $stmt = $this->pdo->prepare("SELECT `email` FROM `users` WHERE `email` = :email");
        $stmt->bindParam(":email",$email, PDO::PARAM_STR);
        $stmt->execute();

        $count = $stmt->rowCount();

        if($count > 0){
          return true;
        }else{
            return false;
        }


    }       
  
}