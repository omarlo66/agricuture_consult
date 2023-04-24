<?php

include_once '../options.php';

if(isset($_POST['username']) && ! isset($_GET['new'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_id = login($username,$password);
    if($user_id){
        setcookie('user',$username,time() + (86400 * 30),'/');
        echo "success";
    }
    else{
        echo 'Invalid username or password';
    }
}
if(isset($_GET['new']) && isset($_POST['username'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobile = $_POST['mobile'];
    if(insert_user($username,$password,$email,$mobile,'','user')){
        echo 'success';
    }
    else{
        echo 'User already exists try another username or email';
    }
}

?>