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
        $box ='<div class="all-tweet">
        <div class="t-show-wrap">	
         <div class="t-show-inner">
            <div class="t-show-popup">
                <div class="t-show-head">
                    <div class="t-show-img">
                        <img src="PROFILE-IMAGE"/>
                    </div>
                    <div class="t-s-head-content">
                        <div class="t-h-c-name">
                            <span><a href="PROFILE-LINK">SCREEN-NAME</a></span>
                            <span>@USERNAMAE</span>
                            <span>POSTED-ON</span>
                        </div>
                        <div class="t-h-c-dis">
                            STATUS
                        </div>
                    </div>
                </div>
            
                <div class="t-show-body">
                  <div class="t-s-b-inner">
                   <div class="t-s-b-inner-in">
                     <img src="TWEET-IMAGE" class="imagePopup"/>
                   </div>
                  </div>
                </div>
                
            </div>
            <div class="t-show-footer">
                <div class="t-s-f-right">
                    <ul> 
                        <li><button><a href="#"><i class="fa fa-share" aria-hidden="true"></i></a></button></li>	
                        <li><button><a href="#"><i class="fa fa-retweet" aria-hidden="true"></i></a></button></li>
                        <li><button><a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a></button></li>
                            <li>
                            <a href="#" class="more"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>
                            <ul> 
                              <li><label class="deleteTweet">Delete Tweet</label></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        </div>
        </div>';

            foreach($tweets as $tweet){
              echo $box;
            }
           
            return $tweets;
           

    }
    public function tweetByid(){
        $dbManager = DBManager::getInstance();
        $pdo = $dbManager->getPdo();
        $stmt =$pdo->prepare("SELECT * FROM `tweets` WHERE `tweetBy` = {$_SESSION['id']} ");
      
        $stmt->execute();
        $tweets = $stmt->fetchAll();
        
        return $tweets;
    }

   
}