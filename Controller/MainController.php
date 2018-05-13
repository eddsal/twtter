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
          
                $userManager = new User();
                $user = $userManager->getUserByUsername($email);
                $userManager->login($email, $data['id']);
                $getFromT = new Tweet();
                $twts =['tweet'=>$getFromT->tweets()];
                $count=$getFromT->countTweet($_SESSION['id']);

              return $this->render('profile.html.twig',$data + $twts + $count );
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
        $id = $_SESSION['id'];
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
                        $getFromU->register($id,$username,$email, $password,$screenName,$profileImage,$profileCover,$followers,$following,$bio,$country,$website);
                        $getFromT = new Tweet();
                        $twts =['tweet'=>$getFromT->tweets()];
                        return $this->render('profile.html.twig', $datau + $twts);    
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

        $getFromT = new Tweet();
        $twts =['tweet'=>$getFromT->tweets()];
        $count=$getFromT->countTweet($_SESSION['id']);
        return $this->render('profile.html.twig', $data + $twts +$count);
        
    }
 
     
    public function searchaction(){
      
    if(isset($_POST['search'])){
        $getFromU = new User();
        $search = $getFromU->getUser($_SESSION['id']);
        $result = $getFromU->search($search);
       
        echo '<div class="nav-right-down-wrap"><ul> ';
    
        foreach($result as $search){
           
            echo '
                        <li>
                          <div class="nav-right-down-inner">
                        <div class="nav-right-down-left">
                          <a href="?action=searchprofile" ><img src="'.$search->profileImage.'"></a>
                       </div>
                       <div class="nav-right-down-right">
                         <div class="nav-right-down-right-headline">
                            <a href="">'.$search->screenName.'</a>
                            <span></span>
                          </div>
                         <div class="nav-right-down-right-body">
                        </div>
                    </div>
                </div> 
            </li>';
          }
          
        }   
    }
    public function searchProfileaction(){
        $getFromU = new User();     
        $search = $getFromU->getUser($_SESSION['id']);
        $result =['result'=>$getFromU->searchProfile($search)];
        //var_dump('<pre>',$result);
        //die();
        return $this->render('searchprofile.html.twig',$result + $search);
    }
    public function settingsAction(){
        $getFromU = new User();
        $getFromT = new Tweet();
      
        $data = $getFromU->getUser($_SESSION['id']);
        $getFromT = new Tweet();
        $dd =['tweet'=>$getFromT->tweetByid()];
        $count=$getFromT->countTweet($_SESSION['id']);
       
      //  var_dump('<pre>',$data['id']);
        return $this->render('pEdit.html.twig',$data + $dd + $count);
     //   return $this->render('searchprofile.html.twig',$data + $dd + $count);




    }
    public function updateAction(){
        $getFromU = new User();
        if(isset($_POST['savez'])){
       
         $screenName = $_POST['screenName'];
         $bio = $_POST['bio'];
         $country = $_POST['country'];
         $website = $_POST['website'];
      
         $data = $getFromU->getUser($_SESSION['id']);
         $id=$_SESSION['id'];
      
         $getFromU->update('users', $id,array('screenName' => $screenName,'bio' => $bio, 'country' => $country,'website' => $website));   
        }
        if(isset($_FILES['profileImage'])){
            $profileImage = $_FILES['profileImage'];
            $root =$getFromU->uploadImage($_FILES['profileImage']);
            $getFromU->update('users', $id, array('profileImage'=> $root));   
        }
        if(isset($_FILES['profileCover'])){
            $profileCover = $_FILES['profileCover'];
            $root =$getFromU->uploadImage($_FILES['profileCover']);
            $getFromU->update('users', $id, array('profileCover'=> $root));   
        }   
        $getFromT = new Tweet();
           $dd =['tweet'=>$getFromT->tweetByid()];
         $count=$getFromT->countTweet($_SESSION['id']);
         return $this->render('pEdit.html.twig',$data+$dd+$count);
     }
     
    public function tweetAction(){
        if(isset($_POST['tweet'])){
        $tweetId='';
        $retweetId=0;
        $retweetBy=0;
        $likeCount=0;
        $retweetCount=0;
        $postedOn= date("Y-m-d H:i:s");
        $retweetMsg='';
        $tweetImage = '';
        $getFromU = new User();
        $data =$getFromU->getUser($_SESSION['id']);
        $id = $_SESSION['id'];
        $status = $_POST['status'];
        
        $getFromT = new Tweet();
        $dd =['tweet'=>$getFromT->tweets()];
           $count=$getFromT->countTweet($_SESSION['id']);
      
        if(strlen($status) > 140){
            $data['error']="text too long";
            return $this->render('profile.html.twig',$data + $dd + $count);
        }if(empty($status)){
            $data['error']= "To Tweet, you should type or insert an image";
            return $this->render('profile.html.twig',$data + $dd + $count);
        } else {
            $data =$getFromU->getUser($_SESSION['id']);
          
           $getFromU->create($tweetId,$status,$id,$retweetId,$retweetBy,$tweetImage,$likeCount,$retweetCount,$postedOn,$retweetMsg);
                 //displaying tweet
          return $this->render('profile.html.twig',$data + $dd + $count);

           } 
        };
        
   }  

   public function deleteAction(){
        if(isset($_POST['deleteTweet'])){
         $getFromT = new Tweet;
         $delete = $getFromT->deleteTweet();
        

        }
   }
   public function retweetAction(){
    if(isset($_POST['showPopup'])){
            $userId =$_SESSION['id'];
            $tweetId =$_POST['showPopup'];
           // $getId= $_POST['id'];
           $getFromT = new Tweet();
           $retweet =$getFromT->getRetweet($tweetId);

           var_dump($retweet);
           die();
                
           
       }
   
   }
   public function likeAction(){
    if(isset($_POST['like'])){

        $userId =$_SESSION['id'];
        $tweetId =$_POST['like'];
      //  $getId= $_POST['id'];
       $getFromT = new Tweet();
       $like =$getFromT->like($userId,$tweetId);
      
    }

   }


}