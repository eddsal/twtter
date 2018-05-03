<?php
namespace UserManager;

use Cool\DBManager;
use TweetManger\Tweet;


class User {

    //vraeting search methode
       public function search($search){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("SELECT `id`, `username`, `screenName` , `profileImage`, `profileCover` FROM `users`");
      
        $stmt->execute();
        $search =  $stmt->fetchAll(\PDO::FETCH_OBJ);

      
        return $search;
      
       }
        //check if we have the user mail and password in the db
    public function checklogin($email, $password){
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
    public function login($email, $id)
    {
        $_SESSION['email'] = $email;
        $_SESSION['id'] = $id;
      
    }
    //insertin user data into my database + default photo and cover
    public function register($id,$username,$email, $password,$screenName,$profileImage,$profileCover,$followers,$following,$bio,$country,$website){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      //  $_SESSION['id']=$id;
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
    public function Update($table,$id,$fields = array()){
        $columns = '';
         $i = 1;
        foreach($fields as $name => $values){
            $columns .= "`{$name}` = :{$name}";
            if($i < count($fields)){
                $columns .= ', ';
            }
            $i++;
        }
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $sql = "UPDATE {$table} SET {$columns} WHERE `id` = $id";
            if($stmt = $pdo->prepare($sql)){
                 foreach ($fields as $key => $value){
                    $stmt->bindValue(':'.$key, $value);
            } 

            $stmt->execute();  
        }
    }

    // public function create($table,$fields = array()){
    //     $columns = implode(',', array_keys($fields));
    //     $values = ':'.implode(', :', array_keys($fields));
    //     $sql = "INSERT INTO {$table}  ({$columns}) VALUES ({$values})";
    //     $dbm = DBManager::getInstance();
    //     $pdo = $dbm->getPdo();
    //     if($stmt = $pdo->prepare($sql)){
    //         foreach ($fields as $key => $data){
    //             //$stmt->bindValue($columns);
    //             $stmt->bindValue(':' .$key, $data);
    //         }   
    //         $stmt->execute();
    //         return $stmt;
            
    //     }
    // } 
    public function create($tweetId,$status,$id,$retweetId,$retweetBy,$tweetImage,$likeCount,$retweetCount,$postedOn,$retweetMsg){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("INSERT INTO `tweets`(`tweetID`, `status`, `tweetBy`, `retweetId`, `retweetBy`, `tweetImage`, `likeCount`, `retweetCount`, `postedOn`, `retweetMsg`) VALUES (NULL, :status, :id ,:retweetId,:retweetBy,:tweetImage,:likeCount,:retweetCount,:postedOn,:retweetMsg)");
    
        $stmt->bindParam(":status",$status);
        $stmt->bindParam(":id",$_SESSION['id']);
        $stmt->bindParam(":retweetId",$retweetId);
        $stmt->bindParam(":retweetBy",$retweetBy);
        $stmt->bindParam(":tweetImage",$tweetImage);
        $stmt->bindParam(":likeCount",$likeCount);
        $stmt->bindParam(":retweetCount",$retweetCount);
        $stmt->bindParam(":postedOn",$postedOn);
        $stmt->bindParam(":retweetMsg",$retweetMsg);
        
         $stmt->execute();
    }
    public function getUser($id){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $result->bindParam(':id',$id);
        $result->execute();
        $post = $result->fetch();

        return $post;
    }
    
    public function getUserByUsername($screenName){

        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->prepare('SELECT * FROM users WHERE screenName = :screenName');
        $result->execute([':screenName' => $screenName]);
        $users = $result->fetch();

        return $users;
    }
    public function uploadImage($file){

        $fileName = basename($file['name']);
        $fileTmp =$file['tmp_name'];
        $fileSize =$file['size'];
        $error = $file['error'];

        $ext = explode('.', $fileName);
        $ext = strtolower(end($ext));
        $allowedExt = array('jpg','png','jpeg');

        if(in_array($ext,$allowedExt)===true){
            if($error === 0){
                if($fileSize <= 209272152){
                    $fileRoot = './users/' .$fileName;
                    move_uploaded_file($fileTmp, $fileRoot);
                    return $fileRoot;
                }   
            }
        }
    }
    
}