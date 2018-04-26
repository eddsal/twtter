<?php

namespace Controller;

require_once('Model/UserManager.php');

require_once('Model/TweetManager.php');
use UserManager\User;
use Cool\BaseController;
use TweetManager\Tweet;

//require_once('config/init.php');

class MainController extends BaseController
{
    public function homeAction()
    {
        return $this->render('home.html.twig');
    }
    // seeing the data if eveything is ok or not 
    public function loginAction(){
        if(isset($_POST['login']) && !empty($_POST['login'])){
            $email = $_POST['email'];
            $password = $_POST['password'];
            if(empty($email) || empty($password)){
                $data['error'] = "please fill in the blank";
            }   
            if(!empty($email) || !empty($password)){
                $getFromU = new User();
                $data = $getFromU->checklogin($email, $password);

                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $data['error'] = "Invalid format";
                }
                if($getFromU->checklogin($email, $password) === false){
                     $data['error'] = "Email or Password is incorrect!!";
                    return $this->render('home.html.twig', $data);
                  }       
            }else{
                $data['error'] = "please enter Email and Password ";
                return $this->render('home.html.twig', $data);
            }       
            if (true == $data) {
              //  var_dump($data['id']);
                //die;
               
                $userManager = new User();
                $user = $userManager->getUserByUsername($email);
                $userManager->login($email, $data['id']);
              //  $logManager->writeToLog('connect to account with the id ' . $user['id'], false);
              return $this->render('profile.html.twig',$data );
        } 
     }
  }
    //checking register inpits
    public function registerAction(){  
        $username='';
        $profileImage='assets/images/profileimage.png';
        $profileCover='assets/images/profileCover.png';
        $following=0;
        $followers=0;
        $bio='';
        $country='';
        $website='';
        if(isset($_POST['signup'])){
            $screenName=$_POST['screenName'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $data = [];
    
            if(empty($screenName) || empty($password) || empty($email))
            { 
                $data['error'] = 'ALL field are requierd';
            }else{
                $getFromU = new User();
                if(!filter_var($email)){
                    $data['error']  = 'Invalid format';
                }else if(strlen($screenName) < 6){
                    $data['error']  = 'Name must be at least  6 chracters';
                }else if(strlen($password) < 5){
                    $data['error']= 'Password is too short';
                }else{
                    if($getFromU->checkEmail($email) === true){
                        $data['error']= 'Email is already in use';
                    }else{

                        $datau= [        
                        'username'=>$username,
                        'email'=>$email,
                        'screenName'=>$screenName,
                        'profileImage'=>$profileImage,
                        'profileCover'=>$profileCover,
                        'followers'=>$followers,
                        'following'=>$following,
                        'bio'=>$bio,
                        'country'=>$country,
                        'website'=>$website
                        ];   
                       // array($username,$email, $password,$screenName,$profileImage,$profileCover,$followers,$following,$bio,$country,$website);
                        $getFromU->register($id,$username,$email, $password,$screenName,$profileImage,$profileCover,$followers,$following,$bio,$country,$website);
                        return $this->render('profile.html.twig', $datau);    
                    }
                }
            }
            return $this->render('home.html.twig', $data);
        
        }
    }
    public function logoutaction()
    {
        $getFromU = new User();
        $getFromU->logout();
        return $this->redirectToRoute('home');
    }

    public function profileAction(){
        $getFromU = new User();
        $data = $getFromU->getUser($_SESSION['id']);
        return $this->render('profile.html.twig', $data);
    }
    public function tweetAction(){
        if(isset($_POST['tweetBtn'])){

            $getFromU = new User();
            $data = $getFromU->getUser($_SESSION['id']);
            $tweetImage = '';
            $status = $_POST['status'];
            if(strlen($status) > 140){
                $data['error']="text too long";
                return $this->render('profile.html.twig', $data);
            }if(empty($status)){
                $data['error']= "To Tweet, you should type or insert an image";
                return $this->render('profile.html.twig', $data);
            } else {
            $ee =  $getFromU->create('tweets', array('status' => $status, 'tweetBy' => $_SESSION['id'], 'tweetImage' => $tweetImage, 'postedOn' => date('Y-m-d H:i:s') ));
             var_dump('<pre>',$ee);
             die();
                return $this->render('profile.html.twig', $data);
            }  
          $getFromU = new Tweet();
          $tweet =$getFromU->tweets($tweets);
          foreach($tweets as $tweet){
              echo '<div class="all-tweet">
              <div class="t-show-wrap">
                  <div class="t-show-inner">
                      <!-- this div is for retweet icon 
                  <div class="t-show-banner">
                      <div class="t-show-banner-inner">
                          <span><i class="fa fa-retweet" aria-hidden="true"></i></span><span>Screen-Name Retweeted</span>
                      </div>
                  </div>
                  -->
                      <div class="t-show-popup">
                          <div class="t-show-head">
                              <div class="t-show-img">
                                  <img src="PROFILE-IMAGE" />
                              </div>
                              <div class="t-s-head-content">
                                  <div class="t-h-c-name">
                                      <span>
                                          <a href="PROFILE-LINK">SCREEN-NAME</a>
                                      </span>
                                      <span>@USERNAMAE</span>
                                      <span>POSTED-ON</span>
                                  </div>
                                  <div class="t-h-c-dis">
                                      STATUS
                                  </div>
                              </div>
                          </div>
                          <!--tweet show head end-->
                          <div class="t-show-body">
                              <div class="t-s-b-inner">
                                  <div class="t-s-b-inner-in">
                                      <img src="TWEET-IMAGE" class="imagePopup" />
                                  </div>
                              </div>
                          </div>
                          <!--tweet show body end-->
                      </div>
                      <div class="t-show-footer">
                          <div class="t-s-f-right">
                              <ul>
                                  <li>
                                      <button>
                                          <a href="#">
                                              <i class="fa fa-share" aria-hidden="true"></i>
                                          </a>
                                      </button>
                                  </li>
                                  <li>
                                      <button>
                                          <a href="#">
                                              <i class="fa fa-retweet" aria-hidden="true"></i>
                                          </a>
                                      </button>
                                  </li>
                                  <li>
                                      <button>
                                          <a href="#">
                                              <i class="fa fa-heart-o" aria-hidden="true"></i>
                                          </a>
                                      </button>
                                  </li>
                                  <li>
                                      <a href="#" class="more">
                                          <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                      </a>
                                      <ul>
                                          <li>
                                              <label class="deleteTweet">Delete Tweet</label>
                                          </li>
                                      </ul>
                                  </li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
          </div>';
            
        };    
     }       
  } 
     
    public function searchaction(){
    if(isset($_POST['search'])){
        $getFromU = new User();
        $search = $getFromU->getUser();
        $result = $getFromU->search($search);
        echo '<div class="nav-right-down-wrap"><ul> ';
    
        foreach($result as $search){
            echo '  <li>
                      <div class="nav-right-down-inner">
                        <div class="nav-right-down-left">
                          <a href="'.$data->username.'"><img src="'.$data->profileImage.'"></a>
                       </div>
                       <div class="nav-right-down-right">
                         <div class="nav-right-down-right-headline">
                            <a href="'.$user->username.'">.$user->screenname.</a><span>@USERNAME</span>
                          </div>
                         <div class="nav-right-down-right-body">
                        </div>
                    </div>
                </div> 
            </li>
            </ul>
            </div> 
    ';
          }
        }
    }
    public function settingsAction(){
        $getFromU = new User();
        $data = $getFromU->getUser($_SESSION['id']);
        //var_dump('<pre>',$data);
      //  var_dump('<pre>',$data['id']);
        return $this->render('pEdit.html.twig',$data);


    }
    public function updateAction(){
        if(isset($_POST['savez'])){
         $_SESSION['id'] =$id ;
        $getFromU = new User();
        $user = $getFromU->getUser($_SESSION['id']);
        $getFromU->update('users', $id, array(`username` => 'eddy'));
        var_dump($getFromU->update('users', $id, array(`username` => 'eddy')));

       
            return $this->render('profile.html.twig', $data);
    }
   
}
}