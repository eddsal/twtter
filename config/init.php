<?php 
    include 'config/parameters.yml';
    include 'Model/UserManager.php';

    //include 'classes/tweet.php';
    //include 'classes/follow.php';

    global $pdo;

    session_start();

    $getFromU = new User($pdo);
    //$getFromU = new Tweet($pdo);
    //$getFromU = new Follow($pdo);

    define("BASE_URL", "http://localhost/myTwitter")

?>
