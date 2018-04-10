<?php

namespace Controller;


use Cool\BaseController;
require_once('Model/UserManager.php');
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
    
            if(!empty($email) || !empty($password)){
                $getFromU = new User();
                $email = $getFromU->checkInput($email);
                $password = $getFromU->checkInput($password);
    
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $data['error'] = "Invalid format";
                }else{
                    if($getFromU->login($email, $password) === false){
                        $data['error'] = "Email or Password is incorrect!!";
                  }
                }
            }else{
                $data['error'] = "please enter Email Or Password ";
                return $this->render('home.html.twig', $data);
            }
        }
        return $this->render('login.html.twig');
    }
}
