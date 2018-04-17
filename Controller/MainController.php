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
            //return var_dump($_SESSION);         
            if(empty($email) || empty($password)){
                $data['error'] = "please fill in the blank";
            }
           
            if(!empty($email) || !empty($password)){
                $getFromU = new User();
                $email = $getFromU->checkInput($email);
                $password = $getFromU->checkInput($password);
                $data = [];
  
                $username='';
                $profileImage='assets/images/profileimage.png';
                $profileCover='assets/images/profileCover.png';
                $following= 
               /// $followers= $getFromU->register($id);
                $bio='';
                $country='';
                $website='';
                
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $data['error'] = "Invalid format";
                }
                if($getFromU->login($email, $password) === false){
                     $data['error'] = "Email or Password is incorrect!!";
                    return $this->render('home.html.twig', $data);
                  }       
            }else{
                $data['error'] = "please enter Email and Password ";
                return $this->render('home.html.twig', $data);
            }
            $datau= [
                'username'=>$username,
                'email'=>$email,
                //'screenName'=>$screenName,
                'profileImage'=>$profileImage,
                'profileCover'=>$profileCover,
               // 'followers'=>$followers,
                'following'=>$following,
                'bio'=>$bio,
                'country'=>$country,
                'website'=>$website,
               
            
                ];
               
                 


               
    
        
          
   
            return $this->render('profile.html.twig', $datau);
          
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
                $email = $getFromU->checkInput($email);
                $screenName = $getFromU->checkInput($screenName);
                $password = $getFromU->checkInput($password);
               
    
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
                        $_SESSION['id'] = $id;
                        return var_dump($id);
                        die();
                
                        return $this->render('profile.html.twig', $data);
                    
                    
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
        
       // $getFromU = new User();
      //  $id= $_SESSION['id'];
        //$user = $getFromU->userData($id);

        //var_dump($user);

    }
        public function tweetAction(){
            
            if(isset($_POST['tweetBtn'])){
                return $this->render('profile.html.twig');

           $getFromU = new User();
           $getFromU-> checkInput($_POST['status']);
           $tweetImage = '';
             if(strlen($status) > 140){
                   $data['error']="text too long";
                   return $this->render('profile.html.twig', $data);

               }
               $getFromU->create('tweets', array('status' => $status, 'tweetBy' => $id, 'tweetImage' => $tweetImage, 'postedOn' => date('Y-m-d H:i:s') ));

           }else{
               $data['error']= "To Tweet, you should type or insert an image";
               return $this->render('profile.html.twig', $data);
           }
           
       } 
        
    
    public function searchaction(){
    if(isset($_POST['search'])){
        var_dump("ssaas");
        $getFromU = new User();
        $search = $getFromU->checkInput($_POST['search']);
        $result = $getFromU->search($search);
    
        echo '<div class="nav-right-down-wrap"><ul> ';
    
        foreach($result as $id){
            echo '  <li>
                      <div class="nav-right-down-inner">
                        <div class="nav-right-down-left">
                          <a href="'.$id->username.'"><img src="'.$id->profileImage.'"></a>
                       </div>
                       <div class="nav-right-down-right">
                         <div class="nav-right-down-right-headline">
                            <a href="'.$id->username.'">.$user->screenname.</a><span>@USERNAME</span>
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
    
    
   
}
