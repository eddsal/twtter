<?php 

require_once('Model/UserManager.php');

require_once('Model/TweetManager.php');
use UserManager\User;
use Cool\BaseController;
use TweetManager\Tweet;

public function searchaction(){

if(isset($_POST['search']) && !empty($_POST['search'])){
    var_dump("ssaas");
    die();
    $getFromU = new User();
    $search = $getFromU->checkInput($_POST['search']);
    $result = $getFromU->search($search);

    echo '<div class="nav-right-down-wrap"><ul> ';

    foreach($result as $user){
        echo '  <li>
                  <div class="nav-right-down-inner">
                    <div class="nav-right-down-left">
                      <a href="'.BASE_URL.$user->username.'"><img src="'.BASE_URL.$user->profileImage.'"></a>
                   </div>
                   <div class="nav-right-down-right">
                     <div class="nav-right-down-right-headline">
                        <a href="'.BASE_URL.$user->username.'">.$user->screenname.</a><span>@USERNAME</span>
                      </div>
                     <div class="nav-right-down-right-body">
                    </div>
                </div>
            </div> 
        </li>
        </ul>
        </div> 
'
       
    }
  }
})







?>